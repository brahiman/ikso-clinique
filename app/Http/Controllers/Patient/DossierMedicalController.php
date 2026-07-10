<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DossierMedicalController extends Controller
{
    public function index()
    {
        $patients = Auth::user()->patients()->with([
            'antecedents',
            'consultations.medecin.user',
            'rendezVous'
        ])->get();

        return view('patient.dossier-medical.index', compact('patients'));
    }

    public function show($patientId)
    {
        $patient = Auth::user()->patients()
            ->with([
                'antecedents',
                'consultations.medecin.user',
                'rendezVous.medecin.user',
                'examens'
            ])
            ->findOrFail($patientId);

        return view('patient.dossier-medical.show', compact('patient'));
    }
}
