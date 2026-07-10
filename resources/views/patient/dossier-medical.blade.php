@extends('layouts.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <h4>Mon Dossier Médical</h4>

            @foreach(Auth::user()->patients as $patient)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>{{ $patient->prenom }} {{ $patient->nom }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Informations Générales</h6>
                                <p><strong>Groupe sanguin :</strong> {{ $patient->groupe_sanguin }}</p>
                                <p><strong>Date de naissance :</strong> {{ $patient->date_naissance?->format('d/m/Y') }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6>Antécédents</h6>
                                @if($patient->dossierMedical)
                                    <p>{{ $patient->dossierMedical->antecedents_familiaux ?? 'Aucun antécédent déclaré' }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
