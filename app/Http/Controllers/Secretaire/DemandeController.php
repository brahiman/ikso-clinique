<?php

namespace App\Http\Controllers\Secretaire;

use App\Http\Controllers\Controller;
use App\Models\DemandeConsultation;
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

        $demande->update([
            'medecin_id' => $request->medecin_id,
            'statut' => 'affectee',
            'date_affectation' => now(),
            'secretaire_id' => auth()->id()
        ]);

        return redirect()->route('secretaire.demandes.index')
            ->with('success', 'Demande affectée avec succès au médecin.');
    }

    public function show(DemandeConsultation $demande)
    {
        $demande->load(['patient', 'medecin.user']);

        return view('secretaire.demandes.show', compact('demande'));
    }


}
