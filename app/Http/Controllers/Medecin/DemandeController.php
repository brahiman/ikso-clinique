<?php

namespace App\Http\Controllers\Medecin;

use App\Http\Controllers\Controller;
use App\Mail\ConsultationDistanceMail;
use App\Models\DemandeConsultation;
use App\Models\RendezVous;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class DemandeController extends Controller
{
    public function index(Request $request)
    {
        $medecin = Auth::user()->medecin;
        abort_if(!$medecin, 403);

        $filtre = $request->get('filtre', 'affectee');


        $query = DemandeConsultation::where('medecin_id', $medecin->id)->with('patient');
       // dd($query);

        if ($filtre !== 'toutes') {
            $query->where('statut', $filtre);
        }

        $demandes = $query->latest()->paginate(15)->withQueryString();

        return view('medecin.demandes.index', compact('demandes', 'filtre'));
    }

    public function show(DemandeConsultation $demande)
    {
        $medecin = Auth::user()->medecin;
        abort_if(!$medecin || $demande->medecin_id !== $medecin->id, 403);

        $demande->load('patient');

        return view('medecin.demandes.show', compact('demande'));
    }

    /**
     * Le médecin confirme un créneau : crée le rendez-vous et clôture la demande côté planification.
     */
    public function confirmer(Request $request, DemandeConsultation $demande)
    {
        $medecin = Auth::user()->medecin;
        abort_if(!$medecin || $demande->medecin_id !== $medecin->id, 403);
        abort_if($demande->statut !== 'affectee', 409, 'Cette demande a déjà été traitée.');

        $validated = $request->validate([
            'date_heure' => 'required|date|after:now',
            'duree' => 'nullable|integer|min:10|max:180',
            'notes' => 'nullable|string',
        ]);

        $rendezVous = RendezVous::create([
            'patient_id' => $demande->patient_id,
            'medecin_id' => $medecin->id,
            'demande_consultation_id' => $demande->id,
            'date_heure' => $validated['date_heure'],
            'duree' => $validated['duree'] ?? 30,
            'statut' => 'confirme',
            'motif' => $demande->motif,
            'notes' => $validated['notes'] ?? null,
            'created_by' => Auth::id(),
        ]);

        $demande->update(['statut' => 'confirmee']);

        $modeConsultation = strtolower((string) ($demande->mode_consultation ?? ''));
        if ($modeConsultation === 'distance') {
            $demande->loadMissing('patient');
            if ($demande->patient?->email) {
                try {
                    Mail::to($demande->patient->email)->queue(new ConsultationDistanceMail($demande, $rendezVous));
                } catch (\Throwable $e) {
                    Log::error("Échec d'envoi du mail de consultation à distance #{$demande->id} : ".$e->getMessage()
                        .' — '.$e->getFile().':'.$e->getLine());
                }
            }
        }

        return redirect()->route('medecin.demandes.index')
            ->with('success', 'Rendez-vous confirmé pour le ' . $rendezVous->date_heure->format('d/m/Y à H:i') . '.');
    }
}