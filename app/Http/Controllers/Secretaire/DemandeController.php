<?php

namespace App\Http\Controllers\Secretaire;

use App\Http\Controllers\Controller;
use App\Models\DemandeConsultation;
use App\Models\DossierMedical;
use App\Models\Medecin;
use App\Models\Patient;
use App\Models\Consultation;
use Illuminate\Http\Request;

class DemandeController extends Controller
{
    public function index()
    {
        $demandes = DemandeConsultation::with(['patient', 'medecin.user'])
            ->latest()
            ->get();

        return view('secretaire.demandes.index', compact('demandes'));
    }

    public function affecter(Request $request, DemandeConsultation $demande)
    {
        $request->validate([
            'medecin_id' => 'required|exists:medecins,id'
        ]);

        $medecinId = $request->medecin_id;

        // Affectation de la demande
        $demande->update([
            'medecin_id' => $medecinId,
            'statut' => 'affectee',
            'date_affectation' => now(),
            'secretaire_id' => auth()->id()
        ]);

        // Affectation automatique du patient au médecin
        $demande->patient->medecins()->syncWithoutDetaching([$medecinId]);

        // Création automatique du dossier médical
        if (!$demande->patient->dossierMedical) {
            DossierMedical::create([
                'patient_id' => $demande->patient->id,
                'notes_generales' => 'Dossier médical initié lors de l’affectation.',
            ]);
        }

        return redirect()->route('secretaire.demandes.index')
            ->with('success', 'Demande et patient affectés au médecin avec succès.');
    }
    public function show(DemandeConsultation $demande)
    {
        $demande->load(['patient', 'medecin.user']);

        return view('secretaire.demandes.show', compact('demande'));
    }


}
