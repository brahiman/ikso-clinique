<?php

namespace App\Http\Controllers\Secretaire;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Medecin;
use App\Models\Consultation;
use Illuminate\Http\Request;

class ConsultationController extends Controller
{
    public function index()
    {
        $consultations = Consultation::with(['patient', 'medecin.user'])
            ->latest()
            ->get();

        return view('secretaire.consultations.index', compact('consultations'));
    }

    public function createDirect()
    {
        $patients = Patient::all();
        $medecins = Medecin::with('user')->get();
        return view('secretaire.consultations.create-direct', compact('patients', 'medecins'));
    }

    public function storeDirect(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'medecin_id' => 'required|exists:medecins,id',
            'motif' => 'required|string',
            'observations_accueil' => 'nullable|string',
        ]);

        Consultation::create([
            'patient_id' => $request->patient_id,
            'medecin_id' => $request->medecin_id,
            'date_consultation' => now(),
            'diagnostic' => null,
            'motif_direct' => $request->motif,
            'observations' => $request->observations_accueil,
            'statut' => 'en_cours',
            'est_urgence' => $request->est_urgence ?? false,
        ]);

        return redirect()->route('secretaire.dashboard')
            ->with('success', 'Consultation directe créée avec succès.');
    }
}
