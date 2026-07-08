@extends('layouts.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4>Dossier Médical - {{ $patient->prenom }} {{ $patient->nom }}</h4>
                <a href="{{ route('patient.dossier.index') }}" class="btn btn-secondary">Retour à la liste</a>
            </div>

            <div class="row">

                <!-- Informations Générales -->
                <div class="col-lg-12 mb-4">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5>Informations Générales</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <p><strong>Date de naissance :</strong> {{ $patient->date_naissance?->format('d/m/Y') }}</p>
                                </div>
                                <div class="col-md-3">
                                    <p><strong>Groupe sanguin :</strong> {{ $patient->groupe_sanguin }}</p>
                                </div>
                                <div class="col-md-3">
                                    <p><strong>Téléphone :</strong> {{ $patient->telephone }}</p>
                                </div>
                                <div class="col-md-3">
                                    <p><strong>Adresse :</strong> {{ $patient->adresse ?? 'Non renseignée' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Antécédents Médicaux -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5>Antécédents Médicaux</h5>
                        </div>
                        <div class="card-body">
                            @if($patient->antecedents->isEmpty())
                                <p class="text-muted">Aucun antécédent déclaré.</p>
                            @else
                                <div class="list-group">
                                    @foreach($patient->antecedents as $antecedent)
                                        <div class="list-group-item">
                                            <strong>{{ $antecedent->nom }}</strong>
                                            <span class="badge bg-{{ $antecedent->gravite == 'haute' ? 'danger' : 'warning' }}">{{ $antecedent->type }}</span>
                                            <br>
                                            <small>{{ $antecedent->description }}</small>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Examens Complémentaires -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5>Examens Complémentaires</h5>
                        </div>
                        <div class="card-body">
                            @if($patient->examens->isEmpty())
                                <p class="text-muted">Aucun examen enregistré.</p>
                            @else
                                <div class="list-group">
                                    @foreach($patient->examens as $examen)
                                        <div class="list-group-item">
                                            <strong>{{ $examen->type_examen }}</strong><br>
                                            <small>{{ $examen->description }}</small>
                                            @if($examen->resultats)
                                                <div class="mt-2 text-success"><strong>Résultat :</strong> {{ $examen->resultats }}</div>
                                            @else
                                                <span class="badge bg-warning">En attente de résultat</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Historique des Consultations -->
                <div class="col-lg-12 mt-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>Historique des Consultations</h5>
                        </div>
                        <div class="card-body">
                            @if($patient->consultations->isEmpty())
                                <p class="text-muted">Aucune consultation enregistrée.</p>
                            @else
                                <div class="list-group">
                                    @foreach($patient->consultations as $consult)
                                        <div class="list-group-item">
                                            <strong>{{ $consult->date_consultation->format('d/m/Y') }}</strong> -
                                            Dr. {{ $consult->medecin->user->name }}
                                            <br>
                                            <small>{{ Str::limit($consult->diagnostic ?? $consult->observations ?? 'Pas de diagnostic', 100) }}</small>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
