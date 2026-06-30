<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\DemandeConsultation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DemandeController extends Controller
{
    public function create()
    {
        return view('patient.demande-consultation');
    }

    public function store(Request $request)
    {
        $request->validate([
            'motif' => 'required|string|max:255',
            'symptomes' => 'required|string',
            'urgence' => 'required|in:basse,moyenne,haute',
            'disponibilite_patient' => 'nullable|string',
        ]);

        $demande = DemandeConsultation::create([
            'patient_id' => Auth::user()->patient?->id,   // Lien avec le profil patient
            'motif' => $request->motif,
            'symptomes' => $request->symptomes,
            'urgence' => $request->urgence,
            'disponibilite_patient' => $request->disponibilite_patient,
            'statut' => 'en_attente',
            'secretaire_id' => null,   // Sera rempli par la secrétaire
        ]);

        // Notification à la secrétaire (plus tard avec Laravel Notifications)
        // Pour l'instant, on peut logger ou envoyer un email

        return redirect()->route('patient.dashboard')
            ->with('success', 'Votre demande de consultation a été envoyée avec succès. Vous serez contacté prochainement.');
    }

    // Liste des demandes du patient
    public function index()
    {
        $demandes = DemandeConsultation::where('patient_id', Auth::user()->patient?->id)
            ->latest()
            ->get();

        return view('patient.demandes.index', compact('demandes'));
    }
}
