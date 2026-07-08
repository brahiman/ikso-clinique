@extends('layouts.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0">Mes Dossiers Médicaux</h4>
                <small class="text-muted">Vous et vos proches</small>
            </div>

            @foreach($patients as $patient)
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ $patient->prenom }} {{ $patient->nom }}</h5>
                        <span class="badge bg-light text-dark">{{ $patient->date_naissance?->format('d/m/Y') }}</span>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <h6 class="text-muted">Informations Générales</h6>
                                <p><strong>Téléphone :</strong> {{ $patient->telephone }}</p>
                                <p><strong>Groupe sanguin :</strong> {{ $patient->groupe_sanguin }}</p>
                                <p><strong>Adresse :</strong> {{ $patient->adresse ?? 'Non renseignée' }}</p>
                            </div>

                            <div class="col-md-4">
                                <h6 class="text-muted">Antécédents Médicaux</h6>
                                @if($patient->antecedents->count() > 0)
                                    <ul class="list-unstyled">
                                        @foreach($patient->antecedents->take(4) as $antecedent)
                                            <li class="mb-1">
                                                <strong>{{ $antecedent->nom }}</strong>
                                                <span class="badge bg-{{ $antecedent->gravite == 'haute' ? 'danger' : 'warning' }}">{{ $antecedent->type }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-muted small">Aucun antécédent déclaré.</p>
                                @endif
                            </div>

                            <div class="col-md-5 text-end pt-4">
                                <a href="{{ route('patient.dossier.show', $patient) }}" class="btn btn-primary">
                                    <i class="ri-eye-line"></i> Voir le dossier complet
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            @if($patients->isEmpty())
                <div class="alert alert-info text-center">
                    Aucun dossier médical disponible.
                </div>
            @endif
        </div>
    </div>
@endsection
