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
                                <i class="ri-file-list-3-line me-2 text-primary"></i>
                                Demandes de Consultation
                            </h4>
                            <p class="text-muted mb-0">
                                <i class="ri-information-line me-1"></i>
                                {{ $demandes->where('statut', 'en_attente')->count() }} demande(s) en attente de traitement
                            </p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('secretaire.consultations.direct') }}" class="btn btn-primary">
                                <i class="ri-add-line me-1"></i> Consultation directe
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistiques -->
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
                            <h3 class="mb-0 mt-2">{{ $demandes->where('statut', 'en_attente')->count() }}</h3>
                            <p class="text-muted mb-0">En attente</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="ri-alert-line text-danger fs-3"></i>
                            <h3 class="mb-0 mt-2">{{ $demandes->where('urgence', 'haute')->count() }}</h3>
                            <p class="text-muted mb-0">Urgentes</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="ri-check-double-line text-success fs-3"></i>
                            <h3 class="mb-0 mt-2">{{ $demandes->where('statut', 'acceptee')->count() }}</h3>
                            <p class="text-muted mb-0">Acceptées</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtres -->
            <div class="row mb-3">
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="ri-search-line"></i>
                        </span>
                        <input type="text"
                               id="searchDemande"
                               class="form-control"
                               placeholder="Rechercher patient, motif...">
                    </div>
                </div>
                <div class="col-md-3">
                    <select id="filterStatut" class="form-select">
                        <option value="">Tous les statuts</option>
                        <option value="en_attente">En attente</option>
                        <option value="acceptee">Acceptée</option>
                        <option value="refusee">Refusée</option>
                        <option value="en_cours">En cours</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select id="filterUrgence" class="form-select">
                        <option value="">Toutes les urgences</option>
                        <option value="haute">Urgente</option>
                        <option value="moyenne">Moyenne</option>
                        <option value="basse">Normale</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select id="filterMedecin" class="form-select">
                        <option value="">Tous les médecins</option>
                        @foreach($medecins ?? [] as $medecin)
                            <option value="{{ $medecin->id }}">Dr. {{ $medecin->user->name }}</option>
                        @endforeach
                        <option value="non_assignee">Non assignée</option>
                    </select>
                </div>
            </div>

            <!-- Liste des demandes -->
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    @if($demandes->isEmpty())
                        <div class="text-center py-5">
                            <i class="ri-file-search-line display-1 text-muted"></i>
                            <h5 class="mt-3">Aucune demande</h5>
                            <p class="text-muted">Aucune demande de consultation à traiter</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle" id="demandesTable">
                                <thead class="table-light">
                                <tr>
                                    <th style="width: 12%;">
                                        <i class="ri-calendar-line me-1"></i>Date
                                    </th>
                                    <th style="width: 18%;">
                                        <i class="ri-user-line me-1"></i>Patient
                                    </th>
                                    <th style="width: 22%;">
                                        <i class="ri-file-text-line me-1"></i>Service / Motif
                                    </th>
                                    <th style="width: 10%;">
                                        <i class="ri-alert-line me-1"></i>Urgence
                                    </th>
                                    <th style="width: 12%;">
                                        <i class="ri-flag-line me-1"></i>Statut
                                    </th>
                                    <th style="width: 16%;">
                                        <i class="ri-stethoscope-line me-1"></i>Médecin
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
                                        data-urgence="{{ $demande->urgence }}"
                                        data-medecin="{{ $demande->medecin_id ?? 'non_assignee' }}">
                                        <td>
                                            <strong>{{ $demande->created_at->format('d/m/Y') }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $demande->created_at->format('H:i') }}</small>
                                            @if($demande->created_at->isToday())
                                                <span class="badge bg-primary-subtle text-primary ms-1">Aujourd'hui</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-primary-subtle rounded-circle d-flex align-items-center justify-content-center me-2">
                                                    <span class="text-primary fw-bold">
                                                        {{ strtoupper(substr($demande->patient->prenom ?? 'P', 0, 1)) }}{{ strtoupper(substr($demande->patient->nom ?? '', 0, 1)) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <strong>{{ $demande->patient->prenom ?? '' }} {{ $demande->patient->nom ?? '' }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $demande->patient->telephone ?? '—' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <strong>{{ $demande->service_souhaite ?? 'Non spécifié' }}</strong>
                                            <br>
                                            <small class="text-muted">{{ Str::limit($demande->motif ?? '', 50) }}</small>
                                            @if($demande->disponibilite_patient)
                                                <br>
                                                <small class="text-info">
                                                    <i class="ri-calendar-line me-1"></i>
                                                    {{ $demande->disponibilite_patient }}
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
                                            <span class="badge bg-{{ $demande->statut == 'en_attente' ? 'warning' : 'success' }}">
                                                {{ ucfirst(str_replace('_', ' ', $demande->statut)) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($demande->medecin)
                                                <span class="badge bg-success-subtle text-success">
                                                    <i class="ri-user-star-line me-1"></i>
                                                    Dr. {{ $demande->medecin->user->name }}
                                                </span>
                                            @else
                                                <span class="badge bg-warning-subtle text-warning">
                                                    <i class="ri-user-search-line me-1"></i>
                                                    Non assignée
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <div class="btn-group">
                                                <a href="{{ route('secretaire.demandes.show', $demande) }}"
                                                   class="btn btn-sm btn-outline-primary"
                                                   title="Voir détails"
                                                   data-bs-toggle="tooltip">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                                @if($demande->statut == 'en_attente')
                                                    <button type="button"
                                                            class="btn btn-sm btn-outline-success"
                                                            onclick="affecterDemande({{ $demande->id }})"
                                                            title="Affecter rapidement"
                                                            data-bs-toggle="tooltip">
                                                        <i class="ri-user-add-line"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

{{--                        <!-- Pagination -->--}}
{{--                        @if($demandes->hasPages())--}}
{{--                            <div class="d-flex justify-content-between align-items-center mt-3">--}}
{{--                                <div class="text-muted">--}}
{{--                                    Affichage de {{ $demandes->firstItem() ?? 0 }} à {{ $demandes->lastItem() ?? 0 }} sur {{ $demandes->total() }} demandes--}}
{{--                                </div>--}}
{{--                                <div>--}}
{{--                                    {{ $demandes->links() }}--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        @endif--}}
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Initialiser les tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            // Recherche en temps réel
            $('#searchDemande').on('keyup', function() {
                filterDemandes();
            });

            // Filtres
            $('#filterStatut, #filterUrgence, #filterMedecin').on('change', function() {
                filterDemandes();
            });

            function filterDemandes() {
                var searchValue = $('#searchDemande').val().toLowerCase();
                var statutFilter = $('#filterStatut').val();
                var urgenceFilter = $('#filterUrgence').val();
                var medecinFilter = $('#filterMedecin').val();

                $('.demande-row').each(function() {
                    var rowText = $(this).text().toLowerCase();
                    var rowStatut = $(this).data('statut');
                    var rowUrgence = $(this).data('urgence');
                    var rowMedecin = $(this).data('medecin').toString();

                    var matchSearch = rowText.indexOf(searchValue) > -1;
                    var matchStatut = !statutFilter || rowStatut === statutFilter;
                    var matchUrgence = !urgenceFilter || rowUrgence === urgenceFilter;
                    var matchMedecin = !medecinFilter || rowMedecin === medecinFilter;

                    if (matchSearch && matchStatut && matchUrgence && matchMedecin) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });

                // Afficher un message si aucun résultat
                var visibleRows = $('.demande-row:visible').length;
                if (visibleRows === 0) {
                    if ($('#noResults').length === 0) {
                        $('tbody').append('<tr id="noResults"><td colspan="7" class="text-center py-4">Aucune demande trouvée</td></tr>');
                    }
                } else {
                    $('#noResults').remove();
                }
            }

            // Affectation rapide
            window.affecterDemande = function(demandeId) {
                window.location.href = '/secretaire/demandes/' + demandeId;
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
            border-radius: 4px !important;
        }

        .badge {
            font-weight: 500;
        }

        @media (max-width: 768px) {
            .d-flex.justify-content-between {
                flex-direction: column;
                align-items: flex-start;
            }

            .btn-group {
                display: flex;
                flex-direction: column;
            }

            .btn-group .btn {
                margin: 2px 0;
            }
        }
    </style>
@endsection
