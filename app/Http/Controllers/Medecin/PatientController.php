<?php

namespace App\Http\Controllers\Medecin;

use App\Http\Controllers\Controller;
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
    })
    ->get();

    return view('medecin.patients.index', compact('patients'));
}

    public function show(Patient $patient)
{
    $medecin = auth()->user()->medecin;

    abort_if(!$medecin->patients->contains($patient->id), 403);

  $patient->load([
    'dossierMedical',
    'consultations',
    'ordonnances.medecin.user',
    'demandesExamens',
]);

    return view('medecin.patients.show', compact('patient'));
}
public function storeDossierMedical(Request $request, Patient $patient)
{
    $medecin = auth()->user()->medecin;

    abort_if(!$medecin->patients->contains($patient->id), 403);


    $validated = $request->validate([
        'antecedents_familiaux' => 'nullable|string',
        'allergies' => 'nullable|string',
        'vaccins' => 'nullable|string',
        'notes_generales' => 'nullable|string',
    ]);


    $patient->dossierMedical()->updateOrCreate(
        [
            'patient_id' => $patient->id
        ],
        $validated
    );


    return redirect()
        ->route('medecin.patients.show', $patient)
        ->with('success', 'Dossier médical enregistré.');
}
}
