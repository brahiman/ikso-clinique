@extends('layouts.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <!-- Header -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1">
                                <i class="ri-folder-user-line me-2 text-primary"></i>
                                Mes Dossiers Médicaux
                            </h4>
                            <p class="text-muted mb-0">
                                <i class="ri-team-line me-1"></i>
                                {{ $patients->count() }} dossier(s) disponible(s)
                            </p>
                        </div>
                        <div>
                            <a href="" class="btn btn-primary">
                                <i class="ri-user-add-line me-1"></i> Ajouter un proche
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Liste des dossiers -->
            @forelse($patients as $patient)
                <div class="card border-0 shadow-sm mb-4">
                    <!-- En-tête du dossier -->
                    <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div class="d-flex align-items-center">
                            <div class="avatar-lg bg-primary-subtle rounded-circle d-flex align-items-center justify-content-center me-3">
                                <span class="text-primary fw-bold fs-5">
                                    {{ strtoupper(substr($patient->prenom, 0, 1)) }}{{ strtoupper(substr($patient->nom, 0, 1)) }}
                                </span>
                            </div>
                            <div>
                                <h5 class="mb-1">{{ $patient->prenom }} {{ $patient->nom }}</h5>
                                <div class="d-flex gap-2 flex-wrap">
                                    @if($patient->date_naissance)
                                        <span class="badge bg-info-subtle text-info">
                                            <i class="ri-calendar-line me-1"></i>
                                            {{ $patient->date_naissance->format('d/m/Y') }}
                                            ({{ $patient->date_naissance->age }} ans)
                                        </span>
                                    @endif
                                    @if($patient->sexe == 'M')
                                        <span class="badge bg-primary-subtle text-primary">
                                            <i class="ri-men-line me-1"></i>Masculin
                                        </span>
                                    @elseif($patient->sexe == 'F')
                                        <span class="badge bg-danger-subtle text-danger">
                                            <i class="ri-women-line me-1"></i>Féminin
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('patient.patients.show', $patient) }}"
                               class="btn btn-outline-primary btn-sm"
                               title="Voir le profil">
                                <i class="ri-user-line"></i>
                            </a>
                            <a href="{{ route('patient.dossier.show', $patient) }}"
                               class="btn btn-primary btn-sm">
                                <i class="ri-eye-line me-1"></i> Voir le dossier complet
                            </a>
                        </div>
                    </div>

                    <!-- Corps du dossier -->
                    <div class="card-body">
                        <div class="row g-4">
                            <!-- Informations Générales -->
                            <div class="col-md-4">
                                <div class="p-3 bg-light rounded h-100">
                                    <h6 class="fw-bold mb-3">
                                        <i class="ri-user-line me-2 text-primary"></i>
                                        Informations Générales
                                    </h6>
                                    <div class="mb-2">
                                        <label class="text-muted small mb-0">Téléphone</label>
                                        <p class="mb-0 fw-medium">
                                            @if($patient->telephone)
                                                <i class="ri-phone-line me-1 text-muted"></i>{{ $patient->telephone }}
                                            @else
                                                <span class="text-muted">Non renseigné</span>
                                            @endif
                                        </p>
                                    </div>
                                    <div class="mb-2">
                                        <label class="text-muted small mb-0">Groupe sanguin</label>
                                        <p class="mb-0">
                                            @if($patient->groupe_sanguin)
                                                <span class="badge bg-danger-subtle text-danger">
                                                    <i class="ri-drop-fill me-1"></i>{{ $patient->groupe_sanguin }}
                                                </span>
                                            @else
                                                <span class="text-muted">Non renseigné</span>
                                            @endif
                                        </p>
                                    </div>
                                    <div>
                                        <label class="text-muted small mb-0">Adresse</label>
                                        <p class="mb-0 fw-medium">
                                            @if($patient->adresse)
                                                <i class="ri-map-pin-line me-1 text-muted"></i>{{ $patient->adresse }}
                                            @else
                                                <span class="text-muted">Non renseignée</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Antécédents Médicaux -->
                            <div class="col-md-4">
                                <div class="p-3 bg-light rounded h-100">
                                    <h6 class="fw-bold mb-3">
                                        <i class="ri-health-book-line me-2 text-warning"></i>
                                        Antécédents Médicaux
                                    </h6>
                                    @if($patient->antecedents->count() > 0)
                                        <div class="list-group list-group-flush">
                                            @foreach($patient->antecedents->take(4) as $antecedent)
                                                <div class="list-group-item bg-transparent px-0 py-2">
                                                    <div class="d-flex justify-content-between align-items-start">
                                                        <div>
                                                            <strong class="d-block">{{ $antecedent->nom }}</strong>
                                                            <small class="text-muted">
                                                                {{ Str::limit($antecedent->description ?? '', 40) }}
                                                            </small>
                                                        </div>
                                                        @if($antecedent->type == 'allergie')
                                                            <span class="badge bg-danger-subtle text-danger">Allergie</span>
                                                        @elseif($antecedent->type == 'maladie')
                                                            <span class="badge bg-primary-subtle text-primary">Maladie</span>
                                                        @elseif($antecedent->type == 'chirurgie')
                                                            <span class="badge bg-info-subtle text-info">Chirurgie</span>
                                                        @else
                                                            <span class="badge bg-secondary">{{ $antecedent->type }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        @if($patient->antecedents->count() > 4)
                                            <small class="text-muted">
                                                <i class="ri-more-line me-1"></i>
                                                {{ $patient->antecedents->count() - 4 }} autre(s) antécédent(s)
                                            </small>
                                        @endif
                                    @else
                                        <div class="text-center py-3">
                                            <i class="ri-check-line text-success fs-4"></i>
                                            <p class="text-muted mb-0 small">Aucun antécédent déclaré</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Résumé rapide -->
                            <div class="col-md-4">
                                <div class="p-3 bg-light rounded h-100">
                                    <h6 class="fw-bold mb-3">
                                        <i class="ri-pulse-line me-2 text-success"></i>
                                        Résumé
                                    </h6>
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="text-muted">Consultations</span>
                                            <span class="badge bg-success">{{ $patient->consultations->count() }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="text-muted">Rendez-vous à venir</span>
                                            <span class="badge bg-primary">
                                                {{ $patient->rendezVous->where('date_heure', '>=', now())->count() }}
                                            </span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="text-muted">Ordonnances</span>
                                            <span class="badge bg-info">{{ $patient->ordonnances->count() }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="text-muted">Médecins assignés</span>
                                            <span class="badge bg-warning text-dark">{{ $patient->medecins->count() }}</span>
                                        </div>
                                    </div>
                                    @if($patient->consultations->isNotEmpty())
                                        <div class="border-top pt-2">
                                            <small class="text-muted d-block mb-1">
                                                <i class="ri-time-line me-1"></i>Dernière consultation :
                                            </small>
                                            <small class="fw-medium">
                                                {{ $patient->consultations->sortByDesc('date_consultation')->first()->date_consultation->format('d/m/Y') }}
                                            </small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pied du dossier -->
                    <div class="card-footer bg-white d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <small class="text-muted">
                            <i class="ri-time-line me-1"></i>
                            Dernière mise à jour : {{ $patient->updated_at->diffForHumans() }}
                        </small>
                        <div class="d-flex gap-2">
                            <a href="{{ route('patient.demandes.create-for-patient', $patient) }}"
                               class="btn btn-outline-success btn-sm">
                                <i class="ri-file-add-line me-1"></i> Demande
                            </a>
                            <a href=""
{{--                               {{ route('patient.rendezvous.create', ['patient_id' => $patient->id]) }}--}}
                               class="btn btn-outline-primary btn-sm">
                                <i class="ri-calendar-plus-line me-1"></i> RDV
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="ri-folder-open-line display-1 text-muted"></i>
                        <h5 class="mt-3">Aucun dossier médical disponible</h5>
                        <p class="text-muted">
                            Vous n'avez pas encore de dossier médical ou de proche enregistré.
                        </p>
                        <a href="{{ route('patient.patients.create') }}" class="btn btn-primary mt-2">
                            <i class="ri-user-add-line me-1"></i> Créer mon premier dossier
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
@endsection

@section('scripts')
    <style>
        .avatar-lg {
            width: 55px;
            height: 55px;
            min-width: 55px;
        }

        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }

        .list-group-item {
            transition: background-color 0.2s ease;
        }

        .list-group-item:hover {
            background-color: #f8f9fa !important;
        }

        .badge {
            font-weight: 500;
        }

        .btn-group .btn {
            margin-right: 2px;
        }

        @media (max-width: 768px) {
            .d-flex.justify-content-between {
                flex-direction: column;
                align-items: flex-start;
            }

            .btn {
                width: 100%;
                margin-bottom: 5px;
            }

            .card-header {
                flex-direction: column;
                align-items: flex-start !important;
            }
        }
    </style>
@endsection
