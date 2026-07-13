@extends('layouts.master')

@section('content')
<div class="page-content">
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>
                Dossier du patient :
                {{ $patient->prenom }} {{ $patient->nom }}
            </h2>

            <a href="{{ route('medecin.patients.index') }}"
               class="btn btn-secondary">
                Retour
            </a>
        </div>

        <div class="row">

            {{-- Informations patient --}}
            <div class="col-lg-4">

                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Informations générales</h5>
                    </div>

                    <div class="card-body">

                        <p>
                            <strong>Nom :</strong><br>
                            {{ $patient->nom }}
                        </p>

                        <p>
                            <strong>Prénom :</strong><br>
                            {{ $patient->prenom }}
                        </p>

                        <p>
                            <strong>Sexe :</strong><br>
                            {{ $patient->sexe }}
                        </p>

                        <p>
                            <strong>Date de naissance :</strong><br>
                            {{ optional($patient->date_naissance)->format('d/m/Y') }}
                        </p>

                        <p>
                            <strong>Téléphone :</strong><br>
                            {{ $patient->telephone }}
                        </p>

                        <p>
                            <strong>Email :</strong><br>
                            {{ $patient->email ?? '-' }}
                        </p>

                        <p>
                            <strong>Adresse :</strong><br>
                            {{ $patient->adresse ?? '-' }}
                        </p>

                        <p class="mb-0">
                            <strong>Groupe sanguin :</strong><br>
                            {{ $patient->groupe_sanguin }}
                        </p>

                    </div>
                </div>

            </div>

            {{-- Dossier médical --}}
            <div class="col-lg-8">

                <div class="card">

                    <div class="card-header">
                        <h5 class="mb-0">
                            Dossier médical
                        </h5>
                    </div>

                    <div class="card-body">

                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form
                            method="POST"
                            action="{{ route('medecin.patients.dossier.store',$patient) }}">

                            @csrf

                            <div class="mb-3">

                                <label class="form-label">
                                    Antécédents familiaux
                                </label>

                                <textarea
                                    class="form-control"
                                    rows="4"
                                    name="antecedents_familiaux">{{ old('antecedents_familiaux',$patient->dossierMedical->antecedents_familiaux ?? '') }}</textarea>

                            </div>

                            <div class="mb-3">

                                <label class="form-label">
                                    Allergies
                                </label>

                                <textarea
                                    class="form-control"
                                    rows="3"
                                    name="allergies">{{ old('allergies',$patient->dossierMedical->allergies ?? '') }}</textarea>

                            </div>

                            <div class="mb-3">

                                <label class="form-label">
                                    Vaccins
                                </label>

                                <textarea
                                    class="form-control"
                                    rows="3"
                                    name="vaccins">{{ old('vaccins',$patient->dossierMedical->vaccins ?? '') }}</textarea>

                            </div>

                            <div class="mb-3">

                                <label class="form-label">
                                    Notes générales
                                </label>

                                <textarea
                                    class="form-control"
                                    rows="4"
                                    name="notes_generales">{{ old('notes_generales',$patient->dossierMedical->notes_generales ?? '') }}</textarea>

                            </div>

                            <button class="btn btn-primary">
                                Enregistrer le dossier médical
                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </div>

        {{-- Consultations --}}
        <div class="card mt-4">

            <div class="card-header">
                <h5 class="mb-0">
                    Historique des consultations
                </h5>
            </div>

            <div class="card-body">

                <table class="table table-bordered">

                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Diagnostic</th>
                            <th>Statut</th>
                        </tr>
                    </thead>

                    <tbody>

                    @forelse($patient->consultations as $consultation)

                        <tr>

                            <td>
                                {{ $consultation->date_consultation->format('d/m/Y') }}
                            </td>

                            <td>
                                {{ $consultation->diagnostic ?? '-' }}
                            </td>

                            <td>
                                {{ ucfirst($consultation->statut) }}
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="3" class="text-center">
                                Aucune consultation.
                            </td>
                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

        {{-- Ordonnances --}}
        <div class="card mt-4">

            <div class="card-header">
                <h5 class="mb-0">
                    Historique des ordonnances
                </h5>
            </div>

            <div class="card-body">

                <table class="table table-bordered">

                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>Date</th>
                            <th>Médecin</th>
                        </tr>
                    </thead>

                    <tbody>

                    @forelse($patient->ordonnances as $ordonnance)

                        <tr>

                            <td>#{{ $ordonnance->id }}</td>

                            <td>
                                {{ $ordonnance->date_prescription->format('d/m/Y') }}
                            </td>

                            <td>
                                {{ $ordonnance->medecin->user->name }}
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="3" class="text-center">
                                Aucune ordonnance.
                            </td>
                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

        {{-- Examens --}}
        <div class="card mt-4 mb-5">

            <div class="card-header">
                <h5 class="mb-0">
                    Historique des demandes d'examens
                </h5>
            </div>

            <div class="card-body">

                <table class="table table-bordered">

                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>Date</th>
                            <th>Statut</th>
                        </tr>
                    </thead>

                    <tbody>

                    @forelse($patient->demandesExamens as $demande)

                        <tr>

                            <td>#{{ $demande->id }}</td>

                            <td>
                                {{ \Carbon\Carbon::parse($demande->date_demande)->format('d/m/Y') }}
                            </td>

                            <td>
                                {{ ucfirst(str_replace('_',' ',$demande->statut)) }}
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="3" class="text-center">
                                Aucune demande d'examen.
                            </td>
                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>
</div>
@endsection