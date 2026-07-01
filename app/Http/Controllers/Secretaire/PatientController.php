<?php

namespace App\Http\Controllers\Secretaire;

use App\Http\Controllers\Controller;
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
            'sexe' => 'required|in:M,F,Autre',
            'date_naissance' => 'required|date',
            'telephone' => 'required|string|unique:patients,telephone',
            'email' => 'nullable|email|unique:patients,email',
            'adresse' => 'nullable|string',
            'groupe_sanguin' => 'nullable|string',
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

        $patient->medecins()->syncWithoutDetaching([$request->medecin_id]);

        return redirect()->route('secretaire.patients.index')
            ->with('success', 'Patient affecté au médecin avec succès.');
    }
}
