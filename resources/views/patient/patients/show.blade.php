@extends('layouts.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <!-- Header avec navigation -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-2">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('patient.dashboard') }}">
                                            <i class="ri-home-4-line me-1"></i>Accueil
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('patient.patients.index') }}">Mes Patients</a>
                                    </li>
                                    <li class="breadcrumb-item active">{{ $patient->prenom }} {{ $patient->nom }}</li>
                                </ol>
                            </nav>
                            <h4 class="mb-0">
                                <i class="ri-user-heart-line me-2 text-primary"></i>
                                {{ $patient->prenom }} {{ $patient->nom }}
                            </h4>
                        </div>
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="{{ route('patient.dossier.show', $patient) }}" class="btn btn-success">
                                <i class="ri-folder-user-line me-1"></i> Dossier médical
                            </a>
                            <a href="{{ route('patient.demandes.create-for-patient', $patient) }}" class="btn btn-primary">
                                <i class="ri-file-add-line me-1"></i> Faire une demande
                            </a>
                            <a href="{{ route('patient.patients.edit', $patient) }}" class="btn btn-warning">
                                <i class="ri-edit-line me-1"></i> Modifier
                            </a>
                            <a href="{{ route('patient.patients.index') }}" class="btn btn-secondary">
                                <i class="ri-arrow-left-line me-1"></i> Retour
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cartes principales -->
            <div class="row">
                <!-- Informations personnelles -->
                <div class="col-lg-6 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white d-flex align-items-center">
                            <i class="ri-user-line me-2 text-primary"></i>
                            <h5 class="mb-0">Informations personnelles</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">Nom complet</label>
                                    <p class="fw-medium mb-0">{{ $patient->prenom }} {{ $patient->nom }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">Sexe</label>
                                    <p class="mb-0">
                                        @if($patient->sexe == 'M')
                                            <span class="badge bg-primary-subtle text-primary">
                                                <i class="ri-men-line me-1"></i>Masculin
                                            </span>
                                        @elseif($patient->sexe == 'F')
                                            <span class="badge bg-danger-subtle text-danger">
                                                <i class="ri-women-line me-1"></i>Féminin
                                            </span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">Date de naissance</label>
                                    <p class="mb-0">
                                        {{ $patient->date_naissance?->format('d/m/Y') ?? '—' }}
                                        @if($patient->date_naissance)
                                            <small class="text-muted">({{ $patient->date_naissance->age }} ans)</small>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">Téléphone</label>
                                    <p class="mb-0">
                                        @if($patient->telephone)
                                            <i class="ri-phone-line me-1 text-muted"></i>{{ $patient->telephone }}
                                        @else
                                            —
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">Email</label>
                                    <p class="mb-0">
                                        @if($patient->email)
                                            <i class="ri-mail-line me-1 text-muted"></i>{{ $patient->email }}
                                        @else
                                            —
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">Adresse</label>
                                    <p class="mb-0">
                                        @if($patient->adresse)
                                            <i class="ri-map-pin-line me-1 text-muted"></i>{{ $patient->adresse }}
                                        @else
                                            —
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informations médicales -->
                <div class="col-lg-6 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white d-flex align-items-center">
                            <i class="ri-heart-pulse-line me-2 text-danger"></i>
                            <h5 class="mb-0">Informations médicales</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="text-muted small">Groupe sanguin</label>
                                    <p class="mb-0">
                                        @if($patient->groupe_sanguin)
                                            <span class="badge bg-danger-subtle text-danger fs-6">
                                                <i class="ri-drop-fill me-1"></i>{{ $patient->groupe_sanguin }}
                                            </span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-8 mb-3">
                                    <label class="text-muted small">Contact d'urgence</label>
                                    <p class="mb-0">
                                        @if($patient->contact_urgence_nom)
                                            <strong>{{ $patient->contact_urgence_nom }}</strong>
                                            @if($patient->contact_urgence_telephone)
                                                <br>
                                                <small>
                                                    <i class="ri-phone-line me-1 text-muted"></i>
                                                    {{ $patient->contact_urgence_telephone }}
                                                </small>
                                            @endif
                                        @else
                                            —
                                        @endif
                                    </p>
                                </div>
                                <div class="col-12">
                                    <hr>
                                    <label class="text-muted small">Médecins assignés</label>
                                    @if($patient->medecins->isEmpty())
                                        <p class="text-muted mb-0">
                                            <i class="ri-information-line me-1"></i>
                                            Aucun médecin assigné
                                        </p>
                                    @else
                                        <div class="d-flex flex-wrap gap-2 mt-2">
                                            @foreach($patient->medecins as $med)
                                                <span class="badge bg-info-subtle text-info">
                                                    <i class="ri-stethoscope-line me-1"></i>
                                                    Dr. {{ $med->user->name ?? 'N/A' }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rendez-vous et Consultations -->
            <div class="row">
                <!-- Prochains rendez-vous -->
                <div class="col-lg-6 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <i class="ri-calendar-check-line me-2 text-primary"></i>
                                <h5 class="mb-0">Prochains rendez-vous</h5>
                            </div>
                            <span class="badge bg-primary-subtle text-primary">
{{--                                {{ $prochainsRdv->count() ?? 0 }}--}}
                            </span>
                        </div>
                        <div class="card-body">
                            @php
                                $prochainsRdv = $patient->rendezVous
                                    ->where('date_heure', '>=', now())
                                    ->sortBy('date_heure')
                                    ->take(5);
                            @endphp

                            @if($prochainsRdv->isEmpty())
                                <div class="text-center py-4">
                                    <i class="ri-calendar-line fs-1 text-muted"></i>
                                    <p class="text-muted mb-0">Aucun rendez-vous à venir</p>
                                </div>
                            @else
                                <div class="list-group list-group-flush">
                                    @foreach($prochainsRdv as $rdv)
                                        <div class="list-group-item d-flex align-items-center px-0">
                                            <div class="avatar-sm bg-primary-subtle rounded-circle d-flex align-items-center justify-content-center me-3">
                                                <i class="ri-calendar-line text-primary"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div>
                                                        <h6 class="mb-0">
                                                            Dr. {{ $rdv->medecin->user->name ?? 'N/A' }}
                                                        </h6>
                                                        <small class="text-muted">
                                                            {{ $rdv->motif ?? 'Consultation' }}
                                                        </small>
                                                    </div>
                                                    <span class="badge bg-{{ $rdv->statut == 'confirme' ? 'success-subtle text-success' : 'warning-subtle text-warning' }}">
                                                        {{ ucfirst($rdv->statut ?? 'En attente') }}
                                                    </span>
                                                </div>
                                                <div class="mt-1">
                                                    <small>
                                                        <i class="ri-time-line me-1"></i>
                                                        {{ $rdv->date_heure->format('d/m/Y à H:i') }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Consultations récentes -->
                <div class="col-lg-6 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <i class="ri-file-history-line me-2 text-success"></i>
                                <h5 class="mb-0">Consultations récentes</h5>
                            </div>
                            <span class="badge bg-success-subtle text-success">
{{--                                {{ $consultationsRecentes->count() ?? 0 }}--}}
                            </span>
                        </div>
                        <div class="card-body">
                            @php
                                $consultationsRecentes = $patient->consultations
                                    ->sortByDesc('date_consultation')
                                    ->take(5);
                            @endphp

                            @if($consultationsRecentes->isEmpty())
                                <div class="text-center py-4">
                                    <i class="ri-file-search-line fs-1 text-muted"></i>
                                    <p class="text-muted mb-0">Aucune consultation enregistrée</p>
                                </div>
                            @else
                                <div class="list-group list-group-flush">
                                    @foreach($consultationsRecentes as $consult)
                                        <div class="list-group-item d-flex align-items-center px-0">
                                            <div class="avatar-sm bg-success-subtle rounded-circle d-flex align-items-center justify-content-center me-3">
                                                <i class="ri-file-text-line text-success"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0">
                                                    Dr. {{ $consult->medecin->user->name ?? 'N/A' }}
                                                </h6>
                                                <small class="text-muted">
                                                    {{ $consult->date_consultation->format('d/m/Y') }}
                                                </small>
                                            </div>
                                            @if($consult->diagnostic)
                                                <i class="ri-more-line text-muted"></i>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Demandes en cours -->
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <i class="ri-file-list-3-line me-2 text-warning"></i>
                                <h5 class="mb-0">Demandes de consultation</h5>
                            </div>
                            <span class="badge bg-warning-subtle text-warning">
{{--                                {{ $demandes->count() ?? 0 }}--}}
                            </span>
                        </div>
                        <div class="card-body">
                            @php
                                $demandes = $patient->demandesConsultations ?? collect();
                            @endphp

                            @if($demandes->isEmpty())
                                <div class="text-center py-4">
                                    <i class="ri-file-transfer-line fs-1 text-muted"></i>
                                    <p class="text-muted mb-0">Aucune demande enregistrée</p>
                                    <a href="{{ route('patient.demandes.create-for-patient', $patient) }}" class="btn btn-primary btn-sm mt-2">
                                        <i class="ri-add-line me-1"></i> Créer une demande
                                    </a>
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="table-light">
                                        <tr>
                                            <th>Date</th>
                                            <th>Service</th>
                                            <th>Urgence</th>
                                            <th>Statut</th>
                                            <th>Médecin</th>
                                            <th>Actions</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($demandes->sortByDesc('created_at')->take(5) as $demande)
                                            <tr>
                                                <td>
                                                    <strong>{{ $demande->created_at->format('d/m/Y') }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $demande->created_at->format('H:i') }}</small>
                                                </td>
                                                <td>{{ $demande->service_souhaite ?? '—' }}</td>
                                                <td>
                                                    @if($demande->urgence == 'haute')
                                                        <span class="badge bg-danger">Haute</span>
                                                    @elseif($demande->urgence == 'moyenne')
                                                        <span class="badge bg-warning text-dark">Moyenne</span>
                                                    @else
                                                        <span class="badge bg-info">Basse</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($demande->statut == 'en_attente')
                                                        <span class="badge bg-warning text-dark">
                                                            <i class="ri-time-line me-1"></i>En attente
                                                        </span>
                                                    @elseif($demande->statut == 'acceptee')
                                                        <span class="badge bg-success">Acceptée</span>
                                                    @elseif($demande->statut == 'refusee')
                                                        <span class="badge bg-danger">Refusée</span>
                                                    @else
                                                        <span class="badge bg-secondary">{{ $demande->statut }}</span>
                                                    @endif
                                                </td>
                                                <td>Dr. {{ $demande->medecin->user->name ?? '—' }}</td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary" title="Voir détails">
                                                        <i class="ri-eye-line"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Initialisation des tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
        });
    </script>

    <style>
        .avatar-sm {
            width: 45px;
            height: 45px;
            min-width: 45px;
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

        .breadcrumb {
            background: transparent;
            padding: 0;
            margin: 0;
        }

        .breadcrumb-item a {
            color: #6c757d;
            text-decoration: none;
        }

        .breadcrumb-item a:hover {
            color: #0d6efd;
        }

        .breadcrumb-item.active {
            color: #495057;
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
