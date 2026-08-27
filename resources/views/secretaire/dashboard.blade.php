@extends('layouts.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <!-- Header -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div>
                            <h2 class="mb-1">
                                <i class="ri-dashboard-line me-2 text-primary"></i>
                                Accueil Secrétaire
                            </h2>
                            <p class="text-muted mb-0">
                                <i class="ri-calendar-line me-1"></i>
                                {{ now()->locale('fr')->isoFormat('dddd D MMMM YYYY') }}
                            </p>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-primary" onclick="window.location.reload()">
                                <i class="ri-refresh-line me-1"></i> Actualiser
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistiques rapides -->
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-md bg-warning-subtle rounded-circle d-flex align-items-center justify-content-center me-3">
                                    <i class="ri-time-line fs-3 text-warning"></i>
                                </div>
                                <div>
                                    <h3 class="mb-0">{{ $demandesEnAttente->count() }}</h3>
                                    <p class="text-muted mb-0">Demandes en attente</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-md bg-info-subtle rounded-circle d-flex align-items-center justify-content-center me-3">
                                    <i class="ri-calendar-check-line fs-3 text-info"></i>
                                </div>
                                <div>
                                    <h3 class="mb-0">{{ $rendezVousAujourdhui->count() }}</h3>
                                    <p class="text-muted mb-0">RDV aujourd'hui</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-md bg-success-subtle rounded-circle d-flex align-items-center justify-content-center me-3">
                                    <i class="ri-team-line fs-3 text-success"></i>
                                </div>
                                <div>
                                    <h3 class="mb-0">{{ $totalPatients ?? 0 }}</h3>
                                    <p class="text-muted mb-0">Patients</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-md bg-primary-subtle rounded-circle d-flex align-items-center justify-content-center me-3">
                                    <i class="ri-stethoscope-line fs-3 text-primary"></i>
                                </div>
                                <div>
                                    <h3 class="mb-0">{{ $totalMedecins ?? 0 }}</h3>
                                    <p class="text-muted mb-0">Médecins</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h6 class="fw-bold mb-3">
                                <i class="ri-flashlight-line me-2 text-warning"></i>
                                Actions rapides
                            </h6>
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('secretaire.consultations.direct') }}" class="btn btn-success">
                                    <i class="ri-stethoscope-line me-1"></i> Consultation directe
                                </a>
                                <a href="{{ route('secretaire.patients.create') }}" class="btn btn-primary">
                                    <i class="ri-user-add-line me-1"></i> Nouveau patient
                                </a>
                                <a href="" class="btn btn-warning">
                                    <i class="ri-file-add-line me-1"></i> Nouvelle demande
                                </a>
{{--                                <a href="" class="btn btn-info">--}}
{{--                                    <i class="ri-calendar-plus-line me-1"></i> Nouveau RDV--}}
{{--                                </a>--}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Demandes en attente -->
                <div class="col-lg-6 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <i class="ri-time-line me-2 text-warning"></i>
                                <h5 class="mb-0">Demandes en Attente</h5>
                            </div>
                            <span class="badge bg-warning text-dark">
                                {{ $demandesEnAttente->count() }}
                            </span>
                        </div>
                        <div class="card-body">
                            @if($demandesEnAttente->isEmpty())
                                <div class="text-center py-4">
                                    <i class="ri-check-double-line fs-1 text-success"></i>
                                    <p class="text-muted mb-0">Aucune demande en attente</p>
                                </div>
                            @else
                                <div class="list-group list-group-flush">
                                    @foreach($demandesEnAttente->take(5) as $demande)
                                        <div class="list-group-item px-0 d-flex justify-content-between align-items-start">
                                            <div class="d-flex align-items-start">
                                                <div class="avatar-sm bg-warning-subtle rounded-circle d-flex align-items-center justify-content-center me-3">
                                                    <span class="text-warning fw-bold">
                                                        {{ strtoupper(substr($demande->patient->prenom, 0, 1)) }}{{ strtoupper(substr($demande->patient->nom, 0, 1)) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <strong>{{ $demande->patient->prenom }} {{ $demande->patient->nom }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ Str::limit($demande->motif, 50) }}</small>
                                                    <br>
                                                    <small class="text-muted">
                                                        <i class="ri-time-line me-1"></i>
                                                        {{ $demande->created_at->diffForHumans() }}
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="d-flex flex-column gap-1">
                                                @if($demande->urgence == 'haute')
                                                    <span class="badge bg-danger">Urgent</span>
                                                @endif
                                                <a href="{{ route('secretaire.demandes.show', $demande) }}"
                                                   class="btn btn-sm btn-primary">
                                                    <i class="ri-eye-line me-1"></i> Affecter
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @if($demandesEnAttente->count() > 5)
                                    <div class="text-center mt-3">
                                        <a href="{{ route('secretaire.demandes.index') }}" class="btn btn-outline-primary btn-sm">
                                            Voir toutes les demandes ({{ $demandesEnAttente->count() }})
                                        </a>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Rendez-vous du jour -->
                <div class="col-lg-6 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <i class="ri-calendar-check-line me-2 text-info"></i>
                                <h5 class="mb-0">Rendez-vous Aujourd'hui</h5>
                            </div>
                            <span class="badge bg-info text-white">
                                {{ $rendezVousAujourdhui->count() }}
                            </span>
                        </div>
                        <div class="card-body">
                            @if($rendezVousAujourdhui->isEmpty())
                                <div class="text-center py-4">
                                    <i class="ri-calendar-line fs-1 text-muted"></i>
                                    <p class="text-muted mb-0">Aucun rendez-vous aujourd'hui</p>
                                </div>
                            @else
                                <div class="list-group list-group-flush">
                                    @foreach($rendezVousAujourdhui->sortBy('date_heure') as $rdv)
                                        <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center">
                                                <div class="text-center me-3">
                                                    <div class="avatar-sm bg-info-subtle rounded-circle d-flex align-items-center justify-content-center">
                                                        <i class="ri-time-line text-info"></i>
                                                    </div>
                                                </div>
                                                <div>
                                                    <strong>{{ $rdv->date_heure->format('H:i') }}</strong>
                                                    <br>
                                                    <span>{{ $rdv->patient->prenom }} {{ $rdv->patient->nom }}</span>
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                <span class="badge bg-primary-subtle text-primary">
                                                    Dr. {{ $rdv->medecin->user->name }}
                                                </span>
                                                <br>
                                                @if($rdv->statut == 'confirme')
                                                    <small class="text-success">
                                                        <i class="ri-check-line me-1"></i>Confirmé
                                                    </small>
                                                @else
                                                    <small class="text-warning">
                                                        <i class="ri-time-line me-1"></i>En attente
                                                    </small>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Liens rapides -->
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h6 class="fw-bold mb-3">
                                <i class="ri-links-line me-2 text-primary"></i>
                                Accès rapides
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-3 col-sm-6">
                                    <a href="{{ route('secretaire.patients.index') }}" class="text-decoration-none">
                                        <div class="card bg-primary-subtle border-0 h-100">
                                            <div class="card-body text-center py-4">
                                                <i class="ri-user-line fs-1 text-primary"></i>
                                                <h6 class="mt-2 mb-0 text-primary">Gérer les Patients</h6>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <a href="{{ route('secretaire.demandes.index') }}" class="text-decoration-none">
                                        <div class="card bg-success-subtle border-0 h-100">
                                            <div class="card-body text-center py-4">
                                                <i class="ri-file-list-line fs-1 text-success"></i>
                                                <h6 class="mt-2 mb-0 text-success">Voir les Demandes</h6>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <a href="" class="text-decoration-none">
                                        <div class="card bg-info-subtle border-0 h-100">
                                            <div class="card-body text-center py-4">
                                                <i class="ri-calendar-line fs-1 text-info"></i>
                                                <h6 class="mt-2 mb-0 text-info">Gérer les Rendez-vous</h6>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <a href="" class="text-decoration-none">
                                        <div class="card bg-warning-subtle border-0 h-100">
                                            <div class="card-body text-center py-4">
                                                <i class="ri-stethoscope-line fs-1 text-warning"></i>
                                                <h6 class="mt-2 mb-0 text-warning">Voir les Médecins</h6>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <style>
        .avatar-sm {
            width: 40px;
            height: 40px;
            min-width: 40px;
        }

        .avatar-md {
            width: 50px;
            height: 50px;
            min-width: 50px;
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
            background-color: #f8f9fa;
        }

        .badge {
            font-weight: 500;
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
        }
    </style>
@endsection
