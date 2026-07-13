<?php

namespace App\Http\Controllers\Medecin;

use App\Http\Controllers\Controller;
use App\Models\RendezVous;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RendezVousController extends Controller
{
    public function index(Request $request)
    {
        $medecin = Auth::user()->medecin;
        abort_if(!$medecin, 403, 'Aucun profil médecin associé à ce compte.');

        $filtre = $request->get('filtre', 'aujourdhui');

        $query = RendezVous::forCurrentMedecin()->with('patient');

        match ($filtre) {
            'aujourdhui' => $query->aujourdhui(),
            'a_venir' => $query->aVenir(),
            default => null, // 'tous' : pas de filtre supplémentaire
        };

        $rendezVous = $query->orderBy('date_heure')->paginate(15)->withQueryString();

        return view('medecin.rendez-vous.index', compact('rendezVous', 'filtre'));
    }

    public function updateStatut(Request $request, RendezVous $rendezVous)
    {
        $medecin = Auth::user()->medecin;
        abort_if(!$medecin || $rendezVous->medecin_id !== $medecin->id, 403);

        $request->validate([
            'statut' => 'required|in:planifie,confirme,en_cours,termine,annule,reporte',
        ]);

        $rendezVous->update(['statut' => $request->statut]);

        return back()->with('success', 'Statut du rendez-vous mis à jour.');
    }

    /**
     * Redirige vers le formulaire de consultation, en pré-liant le rendez-vous.
     * La création effective de la Consultation est gérée par ConsultationController (partie B).
     */
    public function demarrerConsultation(RendezVous $rendezVous)
    {
        $medecin = Auth::user()->medecin;
        abort_if(!$medecin || $rendezVous->medecin_id !== $medecin->id, 403);

        if ($rendezVous->consultation) {
            return redirect()
                ->route('medecin.consultations.edit', $rendezVous->consultation)
                ->with('info', 'Une consultation existe déjà pour ce rendez-vous.');
        }

        $rendezVous->update(['statut' => 'en_cours']);

        return redirect()->route('medecin.consultations.create', ['rendez_vous' => $rendezVous->id]);
    }
}