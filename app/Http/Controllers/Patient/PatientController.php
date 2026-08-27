<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    public function index()
    {
        $patients = Auth::user()->patients()->latest()->get();

        return view('patient.patients.index', compact('patients'));
    }

    public function edit(Patient $patient)
    {
        // Sécurité : le patient doit appartenir au responsable connecté
        abort_if($patient->responsable_id !== Auth::id(), 403);

        return view('patient.patients.edit', compact('patient'));
    }

    public function update(Request $request, Patient $patient)
    {
        abort_if($patient->responsable_id !== Auth::id(), 403);

        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'sexe' => 'nullable|in:M,F,Autre',
            'date_naissance' => 'nullable|date',
            'telephone' => 'nullable|string',
            'email' => 'nullable|email',
            'adresse' => 'nullable|string',
            'groupe_sanguin' => 'nullable|string',
            'contact_urgence_nom' => 'nullable|string',
            'contact_urgence_telephone' => 'nullable|string',
        ]);

        $patient->update($request->only([
            'nom', 'prenom', 'sexe', 'date_naissance', 'telephone',
            'email', 'adresse', 'groupe_sanguin',
            'contact_urgence_nom', 'contact_urgence_telephone'
        ]));

        return redirect()->route('patient.patients.index')
            ->with('success', 'Informations du patient mises à jour.');
    }

    public function show(Patient $patient)
    {
        abort_if($patient->responsable_id !== Auth::id(), 403);

        $patient->load([
            'medecins.user',
            'consultations.medecin.user',
            'rendezVous.medecin.user',
            'ordonnances.details.medicament',
            'demandesConsultations', // si la relation existe
            'demandesExamens.typesExamens',   // ou details selon ton modèle
            'demandesExamens.details.typeExamen',
        ]);

        return view('patient.patients.show', compact('patient'));
    }
}
