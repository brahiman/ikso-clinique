<?php

namespace App\Http\Controllers\Medecin;

use App\Http\Controllers\Controller;
use App\Models\AntecedentMedical;
use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index()
    {
        $medecin = auth()->user()->medecin;

        abort_if(!$medecin, 403);

        $patients = Patient::whereHas('medecins', function ($query) use ($medecin) {
            $query->where('medecin_id', $medecin->id);
        })->get();

        return view('medecin.patients.index', compact('patients'));
    }

    public function show(Patient $patient)
    {
        $medecin = auth()->user()->medecin;

        abort_if(!$medecin->patients->contains($patient->id), 403);

        $patient->load([
            'dossierMedical.antecedentsMedicaux' => function ($query) {
                $query->latest('date_evenement');
            },
            'consultations',
            'ordonnances.medecin.user',
            'demandesExamens',
        ]);

        return view('medecin.patients.show', compact('patient'));
    }

    /**
     * Mise à jour des notes générales du dossier médical.
     */
    public function updateDossierMedical(Request $request, Patient $patient)
    {
        $medecin = auth()->user()->medecin;

        abort_if(!$medecin->patients->contains($patient->id), 403);

        $validated = $request->validate([
            'notes_generales' => 'nullable|string',
        ]);

        $patient->dossierMedical()->firstOrCreate([], ['notes_generales' => null]);
        $patient->dossierMedical()->update($validated);

        return back()->with('success', 'Notes du dossier médical enregistrées.');
    }

    /**
     * Ajout d'un antécédent médical au dossier du patient.
     */
    public function storeAntecedent(Request $request, Patient $patient)
    {
        $medecin = auth()->user()->medecin;

        abort_if(!$medecin->patients->contains($patient->id), 403);

        $validated = $request->validate([
            'antecedents' => 'required|array|min:1',
            'antecedents.*.type' => 'required|in:maladie,allergie,operation,vaccin,familial,autre',
            'antecedents.*.nom' => 'required|string|max:255',
            'antecedents.*.description' => 'nullable|string',
            'antecedents.*.date_evenement' => 'nullable|date',
            'antecedents.*.gravite' => 'nullable|in:faible,moyenne,haute',
            'antecedents.*.actif' => 'nullable|boolean',
        ]);

        $dossier = $patient->dossierMedical()->firstOrCreate([], ['notes_generales' => null]);

        foreach ($validated['antecedents'] as $antecedent) {
            $dossier->antecedentsMedicaux()->create([
                'type' => $antecedent['type'],
                'nom' => $antecedent['nom'],
                'description' => $antecedent['description'] ?? null,
                'date_evenement' => $antecedent['date_evenement'] ?? null,
                'gravite' => $antecedent['gravite'] ?? null,
                'patient_id' => $patient->id,
                'actif' => !empty($antecedent['actif']),
            ]);
        }

        return back()->with('success', count($validated['antecedents']) > 1
            ? 'Antécédents ajoutés avec succès.'
            : 'Antécédent ajouté avec succès.');
    }

    /**
     * Mise à jour d'un antécédent médical existant.
     */
    public function updateAntecedent(Request $request, Patient $patient, AntecedentMedical $antecedent)
    {
        $medecin = auth()->user()->medecin;

        abort_if(!$medecin->patients->contains($patient->id), 403);
        abort_if($antecedent->dossier_medical_id !== $patient->dossierMedical->id, 404);

        $validated = $request->validate([
            'type' => 'required|in:maladie,allergie,operation,vaccin,familial,autre',
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date_evenement' => 'nullable|date',
            'gravite' => 'nullable|in:faible,moyenne,haute',
            'actif' => 'nullable|boolean',
        ]);

        $antecedent->update([
            'type' => $validated['type'],
            'nom' => $validated['nom'],
            'description' => $validated['description'] ?? null,
            'date_evenement' => $validated['date_evenement'] ?? null,
            'gravite' => $validated['gravite'] ?? null,
            'actif' => $request->boolean('actif'),
        ]);

        return back()->with('success', 'Antécédent mis à jour avec succès.');
    }

    /**
     * Suppression d'un antécédent médical.
     */
    public function destroyAntecedent(Patient $patient, AntecedentMedical $antecedent)
    {
        $medecin = auth()->user()->medecin;

        abort_if(!$medecin->patients->contains($patient->id), 403);
        abort_if($antecedent->dossier_medical_id !== $patient->dossierMedical->id, 404);

        $antecedent->delete();

        return back()->with('success', 'Antécédent supprimé avec succès.');
    }
}