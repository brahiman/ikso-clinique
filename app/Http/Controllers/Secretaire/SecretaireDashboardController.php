<?php

namespace App\Http\Controllers\Secretaire;

use App\Http\Controllers\Controller;
use App\Models\DemandeConsultation;
use App\Models\Patient;
use App\Models\RendezVous;

class SecretaireDashboardController extends Controller
{
    public function index()
    {
        $demandesEnAttente = DemandeConsultation::enAttente()->with('patient')->latest()->take(10)->get();
        $rendezVousAujourdhui = RendezVous::aujourdhui()->with(['patient', 'medecin.user'])->get();
        $nouveauxPatients = Patient::latest()->take(5)->get();

        return view('secretaire.dashboard', compact(
            'demandesEnAttente',
            'rendezVousAujourdhui',
            'nouveauxPatients'
        ));
    }
}
