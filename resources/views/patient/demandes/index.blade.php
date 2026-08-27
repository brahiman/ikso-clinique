@extends('layouts.master')

@section('title', 'Mes Demandes de Consultation')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <!-- Header -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1">
                                <i class="ri-file-list-3-line me-2 text-primary"></i>
                                Mes Demandes de Consultation
                            </h4>
                            <p class="text-muted mb-0">
                                <i class="ri-information-line me-1"></i>
                                Suivez l'état de vos demandes de consultation
                            </p>
                        </div>
                        <a href="{{ route('patient.demandes.create') }}" class="btn btn-primary">
                            <i class="ri-add-line me-1"></i> Nouvelle Demande
                        </a>
                    </div>
                </div>
            </div>

            <!-- Statistiques rapides -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="ri-file-list-3-line text-primary fs-3"></i>
                            <h3 class="mb-0 mt-2">{{ $demandes->count() }}</h3>
                            <p class="text-muted mb-0">Total demandes</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="ri-time-line text-warning fs-3"></i>
                            <h3 class="mb-0 mt-2">
                                {{ $demandes->where('statut', 'en_attente')->count() }}
                            </h3>
                            <p class="text-muted mb-0">En attente</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="ri-check-double-line text-success fs-3"></i>
                            <h3 class="mb-0 mt-2">
                                {{ $demandes->where('statut', 'acceptee')->count() }}
                            </h3>
                            <p class="text-muted mb-0">Acceptées</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="ri-alert-line text-danger fs-3"></i>
                            <h3 class="mb-0 mt-2">
                                {{ $demandes->where('urgence', 'haute')->count() }}
                            </h3>
                            <p class="text-muted mb-0">Urgentes</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Liste des demandes -->
            @if($demandes->isEmpty())
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="ri-file-search-line display-1 text-muted"></i>
                        <h5 class="mt-3">Aucune demande</h5>
                        <p class="text-muted">
                            Vous n'avez pas encore fait de demande de consultation.
                        </p>
                        <a href="{{ route('patient.demandes.create') }}" class="btn btn-primary mt-2">
                            <i class="ri-add-line me-1"></i> Créer ma première demande
                        </a>
                    </div>
                </div>
            @else
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <!-- Filtres -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="input-group">
                                    <span class="input-group-text bg-white">
                                        <i class="ri-search-line"></i>
                                    </span>
                                    <input type="text"
                                           id="searchDemande"
                                           class="form-control"
                                           placeholder="Rechercher par motif...">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <select id="filterStatut" class="form-select">
                                    <option value="">Tous les statuts</option>
                                    <option value="en_attente">En attente</option>
                                    <option value="acceptee">Acceptée</option>
                                    <option value="refusee">Refusée</option>
                                    <option value="en_cours">En cours</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select id="filterUrgence" class="form-select">
                                    <option value="">Toutes les urgences</option>
                                    <option value="haute">Urgente</option>
                                    <option value="moyenne">Moyenne</option>
                                    <option value="basse">Normale</option>
                                </select>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle" id="demandesTable">
                                <thead class="table-light">
                                <tr>
                                    <th style="width: 15%;">
                                        <i class="ri-calendar-line me-1"></i>Date
                                    </th>
                                    <th style="width: 25%;">
                                        <i class="ri-file-text-line me-1"></i>Motif
                                    </th>
                                    <th style="width: 15%;">
                                        <i class="ri-alert-line me-1"></i>Urgence
                                    </th>
                                    <th style="width: 15%;">
                                        <i class="ri-flag-line me-1"></i>Statut
                                    </th>
                                    <th style="width: 20%;">
                                        <i class="ri-stethoscope-line me-1"></i>Médecin assigné
                                    </th>
                                    <th style="width: 10%;" class="text-end">
                                        Actions
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($demandes->sortByDesc('created_at') as $demande)
                                    <tr class="demande-row"
                                        data-statut="{{ $demande->statut }}"
                                        data-urgence="{{ $demande->urgence }}">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-primary-subtle rounded-circle d-flex align-items-center justify-content-center me-3">
                                                    <i class="ri-calendar-line text-primary"></i>
                                                </div>
                                                <div>
                                                    <strong>{{ $demande->created_at->format('d/m/Y') }}</strong>
                                                    <br>
                                                    <small class="text-muted">
                                                        {{ $demande->created_at->format('H:i') }}
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <strong>{{ Str::limit($demande->motif, 40) }}</strong>
                                            @if($demande->service_souhaite)
                                                <br>
                                                <small class="text-muted">
                                                    <i class="ri-hospital-line me-1"></i>
                                                    {{ $demande->service_souhaite }}
                                                </small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($demande->urgence == 'haute')
                                                <span class="badge bg-danger">
                                                    <i class="ri-alert-fill me-1"></i>Urgente
                                                </span>
                                            @elseif($demande->urgence == 'moyenne')
                                                <span class="badge bg-warning text-dark">
                                                    <i class="ri-alert-line me-1"></i>Moyenne
                                                </span>
                                            @else
                                                <span class="badge bg-info">
                                                    <i class="ri-information-line me-1"></i>Normale
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($demande->statut == 'en_attente')
                                                <span class="badge bg-warning text-dark">
                                                    <i class="ri-time-line me-1"></i>En attente
                                                </span>
                                            @elseif($demande->statut == 'acceptee')
                                                <span class="badge bg-success">
                                                    <i class="ri-check-line me-1"></i>Acceptée
                                                </span>
                                            @elseif($demande->statut == 'refusee')
                                                <span class="badge bg-danger">
                                                    <i class="ri-close-line me-1"></i>Refusée
                                                </span>
                                            @elseif($demande->statut == 'en_cours')
                                                <span class="badge bg-primary">
                                                    <i class="ri-loader-line me-1"></i>En cours
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">{{ $demande->statut }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($demande->medecin)
                                                <div class="d-flex align-items-center">
                                                    <i class="ri-user-3-line me-2 text-muted"></i>
                                                    Dr. {{ $demande->medecin->user->name ?? 'N/A' }}
                                                </div>
                                            @else
                                                <span class="text-muted">
                                                    <i class="ri-user-search-line me-1"></i>
                                                    En attente d'assignation
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <div class="btn-group">
                                                <button type="button"
                                                        class="btn btn-sm btn-outline-primary"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#demandeModal{{ $demande->id }}"
                                                        title="Voir détails">
                                                    <i class="ri-eye-line"></i>
                                                </button>
                                                @if($demande->statut == 'en_attente')
                                                    <button type="button"
                                                            class="btn btn-sm btn-outline-danger"
                                                            onclick="confirmCancel({{ $demande->id }})"
                                                            title="Annuler la demande">
                                                        <i class="ri-close-line"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Modal détails -->
                                    <div class="modal fade" id="demandeModal{{ $demande->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header bg-primary text-white">
                                                    <h5 class="modal-title">
                                                        <i class="ri-file-list-3-line me-2"></i>
                                                        Détails de la Demande
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="text-muted small">Date de la demande</label>
                                                        <p class="fw-medium mb-0">
                                                            {{ $demande->created_at->format('d/m/Y à H:i') }}
                                                        </p>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="text-muted small">Motif</label>
                                                        <p class="mb-0">{{ $demande->motif }}</p>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="text-muted small">Service souhaité</label>
                                                        <p class="mb-0">{{ $demande->service_souhaite ?? 'Non spécifié' }}</p>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="text-muted small">Urgence</label>
                                                        <p class="mb-0">
                                                            @if($demande->urgence == 'haute')
                                                                <span class="badge bg-danger">Urgente</span>
                                                            @elseif($demande->urgence == 'moyenne')
                                                                <span class="badge bg-warning text-dark">Moyenne</span>
                                                            @else
                                                                <span class="badge bg-info">Normale</span>
                                                            @endif
                                                        </p>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="text-muted small">Statut</label>
                                                        <p class="mb-0">
                                                            <span class="badge bg-{{ $demande->statut == 'en_attente' ? 'warning' : 'success' }}">
                                                                {{ ucfirst($demande->statut) }}
                                                            </span>
                                                        </p>
                                                    </div>
                                                    <div>
                                                        <label class="text-muted small">Médecin assigné</label>
                                                        <p class="mb-0">
                                                            @if($demande->medecin)
                                                                Dr. {{ $demande->medecin->user->name }}
                                                            @else
                                                                <span class="text-muted">En attente d'assignation</span>
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                                    @if($demande->statut == 'en_attente')
                                                        <button type="button" class="btn btn-danger" onclick="confirmCancel({{ $demande->id }})">
                                                            <i class="ri-close-line me-1"></i> Annuler la demande
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Recherche en temps réel
            $('#searchDemande').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                filterDemandes();
            });

            // Filtre par statut
            $('#filterStatut').on('change', function() {
                filterDemandes();
            });

            // Filtre par urgence
            $('#filterUrgence').on('change', function() {
                filterDemandes();
            });

            function filterDemandes() {
                var searchValue = $('#searchDemande').val().toLowerCase();
                var statutFilter = $('#filterStatut').val();
                var urgenceFilter = $('#filterUrgence').val();

                $('.demande-row').each(function() {
                    var rowText = $(this).text().toLowerCase();
                    var rowStatut = $(this).data('statut');
                    var rowUrgence = $(this).data('urgence');

                    var matchSearch = rowText.indexOf(searchValue) > -1;
                    var matchStatut = !statutFilter || rowStatut === statutFilter;
                    var matchUrgence = !urgenceFilter || rowUrgence === urgenceFilter;

                    if (matchSearch && matchStatut && matchUrgence) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            }

            // Annulation d'une demande
            window.confirmCancel = function(demandeId) {
                if (confirm('Êtes-vous sûr de vouloir annuler cette demande ?')) {
                    var form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '/patient/demandes/' + demandeId + '/cancel';

                    var csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = '{{ csrf_token() }}';

                    form.appendChild(csrfInput);
                    document.body.appendChild(form);
                    form.submit();
                }
            };
        });
    </script>

    <style>
        .avatar-sm {
            width: 40px;
            height: 40px;
            min-width: 40px;
        }

        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }

        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
        }

        .btn-group .btn {
            margin-right: 2px;
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
