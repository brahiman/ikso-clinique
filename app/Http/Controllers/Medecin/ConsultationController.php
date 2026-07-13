<?php

namespace App\Http\Controllers\Medecin;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\DemandeExamen;
use App\Models\Medicament;
use App\Models\Ordonnance;
use App\Models\RendezVous;
use App\Models\TypeExamen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        $rendezVous = RendezVous::with('patient')->findOrFail($request->query('rendez_vous'));
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

        return redirect()->route('medecin.consultations.index')
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

        return redirect()->route('medecin.consultations.index')
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
        ->paginate(2, ['*'], 'ordonnances_page');

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
    //fonction pour stocker les ordonnances et les examens complementaires
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

    DB::transaction(function () use ($validated, $consultation, $medecin) {

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
               // 'instructions' => $validated['instructions'] ?? null,
            ]);

        }

    });

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

        DB::transaction(function () use ($validated, $consultation) {

            $demande = DemandeExamen::create([
                'patient_id'      => $consultation->patient_id,
                'consultation_id' => $consultation->id,
                'date_demande'    => now(),
                'instructions'    => $validated['instructions'] ?? null,
                'statut'          => 'demande',
            ]);

            foreach ($validated['examens'] as $examen) {

                $demande->typesExamens()->attach($examen['id'], [
                    'observation'  => $examen['observation'] ?? null,
                    'resultat'     => null,
                    'date_resultat'=> null,
                ]);

            }

        });

        return redirect()
            ->route('medecin.consultations.show', $consultation)
            ->with('success', 'Demande d\'examens enregistrée avec succès.');
    }
}