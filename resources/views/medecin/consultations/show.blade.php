@extends('layouts.master')

@section('content')
<div class="page-content">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Consultation — {{ $consultation->patient->prenom }} {{ $consultation->patient->nom }}</h2>
            <div>
                <span class="badge bg-{{ $consultation->isTerminee() ? 'success' : 'warning' }}">
                    {{ $consultation->statut }}
                </span>
                @if($consultation->isUrgence())
                    <span class="badge bg-danger">Urgence</span>
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4">
                <div class="card mb-3">
                    <div class="card-body">
                        <h5>Patient</h5>
                        <p class="mb-1"><strong>Nom :</strong> {{ $consultation->patient->prenom }} {{ $consultation->patient->nom }}</p>
                        <p class="mb-1"><strong>Téléphone :</strong> {{ $consultation->patient->telephone }}</p>
                        <p class="mb-1"><strong>Groupe sanguin :</strong> {{ $consultation->patient->groupe_sanguin }}</p>
                        @if($consultation->patient->date_naissance)
                            <p class="mb-0"><strong>Date de naissance :</strong> {{ $consultation->patient->date_naissance->format('d/m/Y') }}</p>
                        @endif
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h5>Origine</h5>
                        @if($consultation->isDirecte())
                            <p class="mb-1"><strong>Type :</strong> Accueil direct</p>
                            <p class="mb-0"><strong>Motif :</strong> {{ $consultation->motif_direct ?? '—' }}</p>
                            @if($consultation->notes_accueil)
                                <p class="mb-0 mt-2"><strong>Notes accueil :</strong> {{ $consultation->notes_accueil }}</p>
                            @endif
                        @else
                            <p class="mb-1"><strong>Type :</strong> Rendez-vous planifié</p>
                            <p class="mb-1"><strong>Date du RDV :</strong> {{ $consultation->rendezVous->date_heure->format('d/m/Y H:i') }}</p>
                            <p class="mb-0"><strong>Motif :</strong> {{ $consultation->rendezVous->motif ?? '—' }}</p>
                        @endif
                        <p class="mb-0 mt-2 text-muted small">Consultation du {{ $consultation->date_consultation->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <h5>Diagnostic</h5>
                        <p class="{{ !$consultation->diagnostic ? 'text-muted' : '' }}">
                            {{ $consultation->diagnostic ?? 'Non renseigné' }}
                        </p>

                        <h5 class="mt-4">Observations</h5>
                        <p class="{{ !$consultation->observations ? 'text-muted' : '' }}">
                            {{ $consultation->observations ?? 'Non renseigné' }}
                        </p>

                        <h5 class="mt-4">Traitement</h5>
                        <p class="{{ !$consultation->traitement ? 'text-muted' : '' }}">
                            {{ $consultation->traitement ?? 'Non renseigné' }}
                        </p>

                        <h5 class="mt-4">Recommandations</h5>
                        <p class="mb-0 {{ !$consultation->recommandations ? 'text-muted' : '' }}">
                            {{ $consultation->recommandations ?? 'Non renseigné' }}
                        </p>
                    </div>
                </div>

                @if(!$consultation->isTerminee())
                    <div class="mt-3">
                        <a href="{{ route('medecin.consultations.edit', $consultation) }}" class="btn btn-primary">
                            Reprendre la consultation
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <div class="mt-3">
            <a href="{{ route('medecin.consultations.index') }}" class="btn btn-outline-secondary">
                ← Retour à mes consultations
            </a>
        </div>
    </div>
</div>
@endsection