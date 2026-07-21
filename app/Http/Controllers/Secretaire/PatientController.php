<?php

namespace App\Http\Controllers\Secretaire;

use App\Http\Controllers\Controller;
use App\Models\DossierMedical;
use App\Models\Patient;
use Illuminate\Http\Request;
use App\Models\Medecin;

class PatientController extends Controller
{
    public function index()
    {
        $patients = Patient::with('user')->latest()->get();
        return view('secretaire.patients.index', compact('patients'));
    }

    public function create()
    {
        return view('secretaire.patients.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'sexe' => 'required|in:M,F',
            'date_naissance' => 'required|date',
            'telephone' => 'required|string|unique:patients,telephone',
            'email' => 'nullable|email|unique:patients,email',
            'adresse' => 'nullable|string',
            'groupe_sanguin' => 'nullable|string',
            'contact_urgence_nom' => 'nullable|string',
            'contact_urgence_telephone' => 'nullable|string',
        ]);

        Patient::create($request->all() + ['created_by' => auth()->id()]);

        return redirect()->route('secretaire.patients.index')
            ->with('success', 'Patient créé avec succès.');
    }

    public function affecterForm(Patient $patient)
    {
        $medecins = Medecin::with('user')->get();
        $medecinsActuels = $patient->medecins->pluck('id');

        return view('secretaire.patients.affecter', compact('patient', 'medecins', 'medecinsActuels'));
    }
    public function affecter(Request $request, Patient $patient)
    {
        $request->validate([
            'medecin_id' => 'required|exists:medecins,id'
        ]);

        $medecinId = $request->medecin_id;

        // Affectation du patient au médecin
        $patient->medecins()->syncWithoutDetaching([$medecinId]);

        // Création automatique du dossier médical
        if (!$patient->dossierMedical) {
            DossierMedical::create([
                'patient_id' => $patient->id,
                'notes_generales' => 'Dossier médical initié lors de l’affectation.',
            ]);
        }

        return redirect()->route('secretaire.patients.index')
            ->with('success', 'Patient affecté et dossier médical initié.');
    }

    public function show(Patient $patient)
    {
        $patient->load('responsable', 'medecins.user', 'antecedents', 'consultations');
        return view('secretaire.patients.show', compact('patient'));
    }

    public function edit(Patient $patient)
    {
        return view('secretaire.patients.edit', compact('patient'));
    }

    public function update(Request $request, Patient $patient)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'sexe' => 'required|in:M,F,Autre',
            'date_naissance' => 'nullable|date',
            'telephone' => 'required|string|unique:patients,telephone,' . $patient->id,
            'email' => 'nullable|email|unique:patients,email,' . $patient->id,
            'adresse' => 'nullable|string',
            'groupe_sanguin' => 'nullable|string',
            'contact_urgence_nom' => 'nullable|string',
            'contact_urgence_telephone' => 'nullable|string',
        ]);

        $patient->update($request->all());

        return redirect()->route('secretaire.patients.index')
            ->with('success', 'Patient mis à jour avec succès.');
    }


}
