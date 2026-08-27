<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\RendezVous;
use App\Models\Consultation;
use App\Models\DemandeConsultation;
use App\Models\Medecin;
use Illuminate\Support\Facades\Auth;

class PatientDashboardController extends Controller
{
    public function index()
    {
        $patients = Auth::user()->patients()->with('medecins.user')->get();
        $patientIds = $patients->pluck('id');
        $user = Auth::user();
        $patientIds = $user->patients->pluck('id');

        // Prochains rendez-vous
        $prochainsRdv = RendezVous::whereIn('patient_id', $patientIds)
            ->AVenir()
            ->with('medecin.user')
            ->orderBy('date_heure')
            ->take(5)
            ->get();

        // Dernières consultations
        $dernieresConsultations = Consultation::whereIn('patient_id', $patientIds)
            ->with('medecin.user')
            ->latest()
            ->take(5)
            ->get();

        // Stats
        $rendezVousAvenir = RendezVous::whereIn('patient_id', $patientIds)
            ->where('date_heure', '>=', now())
            ->count();

        $consultations = Consultation::whereIn('patient_id', $patientIds)->count();

        $demandesEnAttente = DemandeConsultation::whereIn('patient_id', $patientIds)
            ->where('statut', 'en_attente')
            ->count();

        $medecinsAssignes = Medecin::whereHas('patients', function ($q) use ($patientIds) {
            $q->whereIn('patients.id', $patientIds);
        })->count();
        return view('patient.dashboard', compact(
            'patients',
            'prochainsRdv',
            'dernieresConsultations',
            'rendezVousAvenir',
            'consultations',
            'demandesEnAttente',
            'medecinsAssignes'
        ));
    }
}
