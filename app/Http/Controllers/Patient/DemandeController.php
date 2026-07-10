<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\DemandeConsultation;
use App\Models\Patient;
use App\Models\RendezVous;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DemandeController extends Controller
{
    public function index()
    {
        $patient = Auth::user()->patient;

        if (!$patient) {
            return view('patient.demandes.index', ['demandes' => collect()]);
        }

        $demandes = DemandeConsultation::whereHas('patient', function($q) {
            $q->where('responsable_id', Auth::id());
        })->with('patient', 'medecin.user')->latest()->get();

        return view('patient.demandes.index', compact('demandes'));
    }
    public function create()
    {
        return view('patient.demande-consultation');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'telephone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'date_naissance' => 'nullable|date',
            'localisation' => 'required|string',
            'adresse' => 'nullable|string',
            'service_souhaite' => 'required|string',
            'urgence' => 'required|in:basse,moyenne,haute',
            'pref_medecin' => 'nullable|string',
            'date_souhaitee' => 'nullable|date',
            'heure_souhaitee' => 'nullable|string',
            'disponibilite_patient' => 'nullable|string',
            'symptomes' => 'required|string',
        ]);

        // Recherche ou création du patient
        $patient = Patient::firstOrCreate(
            ['telephone' => $validated['telephone']],
            [
                'nom' => $validated['nom'],
                'prenom' => $validated['prenom'],
                'email' => $validated['email'],
                'date_naissance' => $validated['date_naissance'],
                'adresse' => $validated['adresse'] ?? $validated['localisation'],
                'groupe_sanguin' => $request->groupe_sanguin ?? 'Inconnu',
                'contact_urgence_nom' => $request->contact_urgence_nom ?? null,
                'contact_urgence_telephone' => $request->contact_urgence_telephone ?? null,
                'responsable_id' => Auth::id(),
                'created_by' => Auth::id(),
            ]
        );

        // Mise à jour des informations si le patient existait déjà
        $patient->update([
            'nom' => $validated['nom'],
            'prenom' => $validated['prenom'],
            'email' => $validated['email'],
            'adresse' => $validated['adresse'] ?? $validated['localisation'],
        ]);

        // Création de la demande
        $demande = DemandeConsultation::create([
            'patient_id' => $patient->id,
            'service_souhaite' => $validated['service_souhaite'],
            'motif' => $validated['service_souhaite'] . ' - ' . $validated['symptomes'],
            'symptomes' => $validated['symptomes'],
            'urgence' => $validated['urgence'],
            'date_souhaitee' => $validated['date_souhaitee'],
            'disponibilite_patient' => $validated['disponibilite_patient'],
            'pref_medecin' => $validated['pref_medecin'] ?? null,
            'statut' => 'en_attente',
        ]);

        return redirect()->route('patient.dashboard')
            ->with('success', 'Votre demande de rendez-vous a été envoyée avec succès. La secrétaire vous contactera bientôt.');
    }

    public function mesRendezVous()
    {
        $patientIds = Auth::user()->patients->pluck('id');

        $rendezVous = RendezVous::whereIn('patient_id', $patientIds)
            ->with(['patient', 'medecin.user'])
            ->latest()
            ->get();

        return view('patient.rendezvous.index', compact('rendezVous'));
    }

    public function listeConsultation()
    {
        $patientIds = Auth::user()->patients->pluck('id'); // Tous les patients dont il est responsable

        $consultations = Consultation::whereIn('patient_id', $patientIds)
            ->with(['patient', 'medecin.user'])
            ->latest()
            ->get();

        return view('patient.consultations.index', compact('consultations'));
    }
}
