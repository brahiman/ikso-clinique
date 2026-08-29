<?php

namespace App\Http\Controllers\Medecin;

use App\Http\Controllers\Controller;
use App\Mail\DemandeExamenMail;
use App\Mail\OrdonnanceMail;
use App\Models\Consultation;
use App\Models\DemandeExamen;
use App\Models\Medicament;
use App\Models\Ordonnance;
use App\Models\RendezVous;
use App\Models\TypeExamen;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ConsultationController extends Controller
{
    /**
     * Liste des consultations en cours du médecin connecté
     * (qu'elles viennent d'un rendez-vous ou d'un accueil direct par la secrétaire)
     */
    public function index()
    {
        $medecin = Auth::user()->medecin;
        abort_if(!$medecin, 403, 'Aucun profil médecin associé à ce compte.');

        $enCours = Consultation::forCurrentMedecin()
            ->where('statut', 'en_cours')
            ->with('patient')
            ->latest()
            ->get();

        $terminees = Consultation::forCurrentMedecin()
            ->where('statut', 'terminee')
            ->with('patient')
            ->latest()
            ->paginate(15);

        return view('medecin.consultations.index', compact('enCours', 'terminees'));
    }

    /**
     * Formulaire de création, uniquement pour une consultation issue d'un rendez-vous.
     * Les consultations directes sont déjà créées par la secrétaire (statut en_cours).
     */
    public function create(Request $request)
    {
        $medecin = Auth::user()->medecin;
        abort_if(!$medecin, 403);

        $rendezVous = RendezVous::with([
            'patient.dossierMedical.antecedentsMedicaux',
            'patient.consultations',
            'patient.ordonnances',
            'patient.demandesExamens'
        ])->findOrFail($request->query('rendez_vous'));

        abort_if($rendezVous->medecin_id !== $medecin->id, 403);

        if ($rendezVous->consultation) {
            return redirect()->route('medecin.consultations.edit', $rendezVous->consultation);
        }

        return view('medecin.consultations.create', compact('rendezVous'));
    }

    public function store(Request $request)
    {
        $medecin = Auth::user()->medecin;
        abort_if(!$medecin, 403);

        $validated = $request->validate([
            'rendez_vous_id' => 'required|exists:rendez_vous,id',
            'diagnostic' => 'nullable|string',
            'observations' => 'nullable|string',
            'traitement' => 'nullable|string',
            'recommandations' => 'nullable|string',
            'action' => 'required|in:continuer,terminer',
        ]);

        $rendezVous = RendezVous::findOrFail($validated['rendez_vous_id']);
        abort_if($rendezVous->medecin_id !== $medecin->id, 403);
        abort_if($rendezVous->consultation, 409, 'Une consultation existe déjà pour ce rendez-vous.');

        $statut = $validated['action'] === 'terminer' ? 'terminee' : 'en_cours';

        $consultation = Consultation::create([
            'patient_id' => $rendezVous->patient_id,
            'medecin_id' => $medecin->id,
            'rendez_vous_id' => $rendezVous->id,
            'date_consultation' => now(),
            'diagnostic' => $validated['diagnostic'] ?? null,
            'observations' => $validated['observations'] ?? null,
            'traitement' => $validated['traitement'] ?? null,
            'recommandations' => $validated['recommandations'] ?? null,
            'statut' => $statut,
        ]);

        if ($statut === 'terminee') {
            $rendezVous->update(['statut' => 'termine']);
        }

        return redirect()->route('medecin.consultations.show', $consultation)
            ->with('success', $statut === 'terminee' ? 'Consultation terminée.' : 'Consultation enregistrée, à poursuivre.');
    }

    /**
     * Sert à la fois pour les consultations issues d'un RDV et les consultations directes.
     */
    public function edit(Consultation $consultation)
    {

        $medecin = Auth::user()->medecin;
        abort_if(!$medecin || $consultation->medecin_id !== $medecin->id, 403);

        $consultation->load('patient', 'rendezVous');

        return view('medecin.consultations.edit', compact('consultation'));
    }

    public function update(Request $request, Consultation $consultation)
    {
        $medecin = Auth::user()->medecin;
        abort_if(!$medecin || $consultation->medecin_id !== $medecin->id, 403);

        $validated = $request->validate([
            'diagnostic' => 'nullable|string',
            'observations' => 'nullable|string',
            'traitement' => 'nullable|string',
            'recommandations' => 'nullable|string',
            'action' => 'required|in:continuer,terminer',
        ]);

        $statut = $validated['action'] === 'terminer' ? 'terminee' : 'en_cours';

        $consultation->update([
            'diagnostic' => $validated['diagnostic'] ?? null,
            'observations' => $validated['observations'] ?? null,
            'traitement' => $validated['traitement'] ?? null,
            'recommandations' => $validated['recommandations'] ?? null,
            'statut' => $statut,
        ]);

        if ($statut === 'terminee' && $consultation->rendezVous) {
            $consultation->rendezVous->update(['statut' => 'termine']);
        }

        return redirect()->route('medecin.consultations.show', $consultation)
            ->with('success', $statut === 'terminee' ? 'Consultation terminée.' : 'Consultation mise à jour.');
    }

    public function show(Consultation $consultation)
    {
        $medecin = Auth::user()->medecin;

        abort_if(!$medecin || $consultation->medecin_id !== $medecin->id, 403);

        $medicaments = Medicament::all();
        $typesExamens = TypeExamen::all();

        $consultation->load([
            'patient',
            'rendezVous',
        ]);

        $ordonnances = $consultation->ordonnances()
            ->with([
                'medecin',
                'details.medicament',
            ])
            ->latest()
            ->paginate(8, ['*'], 'ordonnances_page');

        $demandesExamens = $consultation->demandesExamens()
            ->with([
                'details.typeExamen',
            ])
            ->latest()
            ->paginate(2, ['*'], 'examens_page');

        return view(
            'medecin.consultations.show',
            compact(
                'consultation',
                'medicaments',
                'typesExamens',
                'ordonnances',
                'demandesExamens'
            )
        );
    }

    /**
     * Stocke une ordonnance et envoie automatiquement le PDF par e-mail
     * au patient si celui-ci a une adresse enregistrée.
     */
    public function storeOrdonnance(Request $request, Consultation $consultation)
    {
        $medecin = Auth::user()->medecin;

        abort_if(!$medecin || $consultation->medecin_id != $medecin->id, 403);

        $validated = $request->validate([
            'instructions' => 'nullable|string',

            'medicaments' => 'required|array|min:1',

            'medicaments.*.id' => 'required|exists:medicaments,id',
            'medicaments.*.quantite' => 'required|string',
            'medicaments.*.posologie' => 'required|string',
            'medicaments.*.duree' => 'required|string',
        ]);

        $ordonnance = DB::transaction(function () use ($validated, $consultation, $medecin) {

            // Création de l'ordonnance
            $ordonnance = Ordonnance::create([
                'patient_id' => $consultation->patient_id,
                'consultation_id' => $consultation->id,
                'medecin_id' => $medecin->id,
                'date_prescription' => now(),
                'notes' => $validated['instructions'] ?? null,
            ]);

            // Création des détails
            foreach ($validated['medicaments'] as $medicament) {

                $ordonnance->details()->create([
                    'medicament_id' => $medicament['id'],
                    'quantite' => $medicament['quantite'],
                    'frequence' => $medicament['posologie'],
                    'duree_jours' => (int) filter_var($medicament['duree'], FILTER_SANITIZE_NUMBER_INT),
                ]);
            }

            return $ordonnance;
        });

        // Envoi automatique du PDF par e-mail au patient
        //$this->envoyerOrdonnanceParMail($ordonnance);

        return redirect()
            ->route('medecin.consultations.show', $consultation)
            ->with('success', 'Ordonnance enregistrée avec succès.');
    }

    public function storeDemandeExamen(Request $request, Consultation $consultation)
    {
        $medecin = Auth::user()->medecin;

        abort_if(!$medecin || $consultation->medecin_id != $medecin->id, 403);

        $validated = $request->validate([
            'instructions' => 'nullable|string',

            'examens' => 'required|array|min:1',

            'examens.*.id' => 'required|exists:type_examens,id',
            'examens.*.observation' => 'nullable|string',
        ]);

        $demande = DB::transaction(function () use ($validated, $consultation) {

            $demande = DemandeExamen::create([
                'patient_id' => $consultation->patient_id,
                'consultation_id' => $consultation->id,
                'date_demande' => now(),
                'instructions' => $validated['instructions'] ?? null,
                'statut' => 'demande',
            ]);

            foreach ($validated['examens'] as $examen) {

                $demande->typesExamens()->attach($examen['id'], [
                    'observation' => $examen['observation'] ?? null,
                    'resultat' => null,
                    'date_resultat' => null,
                ]);
            }

            return $demande;
        });

        // Envoi automatique du PDF par e-mail au patient
        //$this->envoyerDemandeExamenParMail($demande);

        return redirect()
            ->route('medecin.consultations.show', $consultation)
            ->with('success', 'Demande d\'examens enregistrée avec succès.');
    }

    /**
     * Met à jour les résultats des examens d'une demande
     */
    public function updateResultatsExamen(Request $request, Consultation $consultation, DemandeExamen $demande)
    {
        $medecin = Auth::user()->medecin;
        abort_if(!$medecin || $consultation->medecin_id !== $medecin->id, 403);
        abort_if($demande->consultation_id !== $consultation->id, 404);

        $validated = $request->validate([
            'resultats' => 'required|array',
            'resultats.*.id' => 'required',
            'resultats.*.resultat' => 'nullable|string',
            'resultats.*.date_resultat' => 'nullable|date',
        ]);

        DB::transaction(function () use ($validated, $demande) {
            $hasAtLeastOneResult = false;
            $allCompleted = true;

            foreach ($validated['resultats'] as $item) {
                $resultat = !empty($item['resultat']) ? $item['resultat'] : null;
                $dateResultat = $resultat ? ($item['date_resultat'] ?? now()) : null;

                if ($resultat) {
                    $hasAtLeastOneResult = true;
                } else {
                    $allCompleted = false;
                }

                if (method_exists($demande, 'details')) {
                    $demande->details()->where('id', $item['id'])->update([
                        'resultat' => $resultat,
                        'date_resultat' => $dateResultat,
                    ]);
                } elseif (method_exists($demande, 'typesExamens')) {
                    $demande->typesExamens()->updateExistingPivot($item['id'], [
                        'resultat' => $resultat,
                        'date_resultat' => $dateResultat,
                    ]);
                }
            }

            if ($allCompleted && $hasAtLeastOneResult) {
                $demande->update(['statut' => 'termine']);
            } elseif ($hasAtLeastOneResult) {
                $demande->update(['statut' => 'en_cours']);
            }
        });

        return redirect()
            ->route('medecin.consultations.show', ['consultation' => $consultation, 'examens_page' => $request->get('examens_page', 1)])
            ->with('success', 'Résultats d\'examens mis à jour avec succès.');
    }

   
    /**
     * Télécharge / affiche le PDF d'une ordonnance.
     */
    public function ordonnancePdf(Ordonnance $ordonnance)
    {
        $this->autoriserOrdonnance($ordonnance);

        $ordonnance->load(['patient', 'medecin.user', 'medecin.specialites', 'details.medicament']);

        $pdf = Pdf::loadView('pdf.ordonnance', compact('ordonnance'))->setPaper('a4');

        return $pdf->stream('ordonnance-'.$ordonnance->id.'.pdf');
    }

    /**
     * Bouton "Envoyer / Renvoyer par e-mail" dans la liste des ordonnances.
     */
    public function envoyerOrdonnance(Ordonnance $ordonnance)
    {
        $this->autoriserOrdonnance($ordonnance);

        $envoye = $this->envoyerOrdonnanceParMail($ordonnance);

        if (!$envoye) {
            return back()->with('error', "Impossible d'envoyer l'ordonnance : vérifiez que le patient a une adresse e-mail valide.");
        }

        return back()->with('success', 'Ordonnance envoyée par e-mail au patient.');
    }

    /**
     * Télécharge / affiche le PDF d'une demande d'examens.
     */
    public function demandeExamenPdf(DemandeExamen $demande)
    {
        $this->autoriserDemandeExamen($demande);

        $demande->load(['patient', 'consultation.medecin.user', 'consultation.medecin.specialites', 'typesExamens']);

        $pdf = Pdf::loadView('pdf.demande-examen', compact('demande'))->setPaper('a4');

        return $pdf->stream('demande-examen-'.$demande->id.'.pdf');
    }

    /**
     * Bouton "Envoyer / Renvoyer par e-mail" dans la liste des demandes d'examens.
     */
    public function envoyerDemandeExamen(DemandeExamen $demande)
    {
        $this->autoriserDemandeExamen($demande);

        $envoye = $this->envoyerDemandeExamenParMail($demande);

        if (!$envoye) {
            return back()->with('error', "Impossible d'envoyer la demande d'examens : vérifiez que le patient a une adresse e-mail valide.");
        }

        return back()->with('success', "Demande d'examens envoyée par e-mail au patient.");
    }

    // ==========================================================
    // Helpers privés
    // ==========================================================

    private function autoriserOrdonnance(Ordonnance $ordonnance): void
    {
        $medecin = Auth::user()->medecin;
        abort_if(!$medecin || $ordonnance->medecin_id !== $medecin->id, 403);
    }

    private function autoriserDemandeExamen(DemandeExamen $demande): void
    {
        $medecin = Auth::user()->medecin;
        $demande->loadMissing('consultation');
        abort_if(!$medecin || !$demande->consultation || $demande->consultation->medecin_id !== $medecin->id, 403);
    }

    /**
     * Envoie le PDF de l'ordonnance par e-mail si le patient a une adresse valide.
     * Ne lève jamais d'exception : un échec d'envoi ne doit pas casser la création.
     */
    private function envoyerOrdonnanceParMail(Ordonnance $ordonnance): bool
    {
        $ordonnance->loadMissing(['patient', 'medecin.user', 'medecin.specialites', 'details.medicament']);

        if (!$ordonnance->patient?->email) {
            return false;
        }

        try {
            // Envoi asynchrone via les queues
            Mail::to($ordonnance->patient->email)->queue(new OrdonnanceMail($ordonnance));
            $ordonnance->update(['envoye_le' => now()]);
            return true;
        } catch (\Throwable $e) {
            Log::error("Échec d'envoi de l'ordonnance #{$ordonnance->id} : ".$e->getMessage()
                .' — '.$e->getFile().':'.$e->getLine());
            return false;
        }
    }

    /**
     * Envoie le PDF de la demande d'examens par e-mail si le patient a une adresse valide.
     */
    private function envoyerDemandeExamenParMail(DemandeExamen $demande): bool
    {
        $demande->loadMissing(['patient', 'consultation.medecin.user', 'consultation.medecin.specialites', 'typesExamens']);

        if (!$demande->patient?->email) {
            return false;
        }

        try {
            // Envoi asynchrone via les queues
            Mail::to($demande->patient->email)->queue(new DemandeExamenMail($demande));
            $demande->update(['envoye_le' => now()]);
            return true;
        } catch (\Throwable $e) {
            Log::error("Échec d'envoi de la demande d'examens #{$demande->id} : ".$e->getMessage()
                .' — '.$e->getFile().':'.$e->getLine());
            return false;
        }
    }

    /**
     * Modifie une ordonnance existante
     */
    public function updateOrdonnance(Request $request, Ordonnance $ordonnance)
    {
        $medecin = Auth::user()->medecin;
        abort_if(!$medecin || $ordonnance->medecin_id !== $medecin->id, 403);

        $validated = $request->validate([
            'instructions' => 'nullable|string',
            'medicaments' => 'required|array|min:1',
            'medicaments.*.id' => 'required|exists:medicaments,id',
            'medicaments.*.quantite' => 'required|string',
            'medicaments.*.posologie' => 'required|string',
            'medicaments.*.duree' => 'required|string',
        ]);

        DB::transaction(function () use ($validated, $ordonnance) {
            // Mise à jour de l'ordonnance
            $ordonnance->update([
                'notes' => $validated['instructions'] ?? null,
            ]);

            // Suppression des anciens détails
            $ordonnance->details()->delete();

            // Création des nouveaux détails
            foreach ($validated['medicaments'] as $medicament) {
                $ordonnance->details()->create([
                    'medicament_id' => $medicament['id'],
                    'quantite' => $medicament['quantite'],
                    'frequence' => $medicament['posologie'],
                    'duree_jours' => (int) filter_var($medicament['duree'], FILTER_SANITIZE_NUMBER_INT),
                ]);
            }
        });

        return redirect()
            ->route('medecin.consultations.show', $ordonnance->consultation)
            ->with('success', 'Ordonnance mise à jour avec succès.');
    }

    /**
     * Supprime une ordonnance
     */
    public function destroyOrdonnance(Ordonnance $ordonnance)
    {
        $this->autoriserOrdonnance($ordonnance);

        $consultation = $ordonnance->consultation;

        DB::transaction(function () use ($ordonnance) {
            $ordonnance->details()->delete();
            $ordonnance->delete();
        });

        return redirect()
            ->route('medecin.consultations.show', $consultation)
            ->with('success', 'Ordonnance supprimée avec succès.');
    }

    /**
     * Modifie une demande d'examen existante
     */
    public function updateDemandeExamen(Request $request, DemandeExamen $demande)
    {
        $medecin = Auth::user()->medecin;
        $demande->loadMissing('consultation');
        abort_if(!$medecin || !$demande->consultation || $demande->consultation->medecin_id !== $medecin->id, 403);

        $validated = $request->validate([
            'instructions' => 'nullable|string',
            'examens' => 'required|array|min:1',
            'examens.*.id' => 'required|exists:type_examens,id',
            'examens.*.observation' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated, $demande) {
            // Mise à jour de la demande
            $demande->update([
                'instructions' => $validated['instructions'] ?? null,
            ]);

            // Suppression des anciens détails
            $demande->typesExamens()->detach();

            // Création des nouveaux détails
            foreach ($validated['examens'] as $examen) {
                $demande->typesExamens()->attach($examen['id'], [
                    'observation' => $examen['observation'] ?? null,
                    'resultat' => null,
                    'date_resultat' => null,
                ]);
            }
        });

        return redirect()
            ->route('medecin.consultations.show', $demande->consultation)
            ->with('success', 'Demande d\'examens mise à jour avec succès.');
    }

    /**
     * Supprime une demande d'examen
     */
    public function destroyDemandeExamen(DemandeExamen $demande)
    {
        $this->autoriserDemandeExamen($demande);

        $consultation = $demande->consultation;

        DB::transaction(function () use ($demande) {
            $demande->typesExamens()->detach();
            $demande->delete();
        });

        return redirect()
            ->route('medecin.consultations.show', $consultation)
            ->with('success', 'Demande d\'examens supprimée avec succès.');
    }
}

