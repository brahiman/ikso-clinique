<?php

namespace App\Http\Controllers\Medecin;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\DemandeConsultation;
use App\Models\RendezVous;
use Illuminate\Support\Facades\Auth;

class MedecinDashboardController extends Controller
{
    public function index()
    {
        $medecin = Auth::user()->medecin;
        abort_if(!$medecin, 403, 'Aucun profil médecin associé à ce compte.');

        $nombrePatients = $medecin->patients()->count();

        $rendezVousAujourdhui = RendezVous::aujourdhui()
            ->where('medecin_id', $medecin->id)
            ->with('patient')
            ->orderBy('date_heure')
            ->get();

        $demandesQuery = DemandeConsultation::where('medecin_id', $medecin->id)
            ->where('statut', 'affectee'); // en attente de confirmation par le médecin

        $nombreDemandes = $demandesQuery->count();

        $demandeConsultations = (clone $demandesQuery)
            ->with('patient')
            ->latest()
            ->take(5)
            ->get();

        $consultationsEnCours = Consultation::where('medecin_id', $medecin->id)
            ->where('statut', 'en_cours')
            ->count();

        return view('medecin.dashboard', compact(
            'medecin',
            'nombrePatients',
            'rendezVousAujourdhui',
            'demandeConsultations',
            'nombreDemandes',
            'consultationsEnCours'
        ));
    }
}