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
                                <i class="ri-calendar-todo-line me-2 text-primary"></i>
                                Mes Rendez-vous
                            </h4>
                            <p class="text-muted mb-0">
                                <i class="ri-team-line me-1"></i>
                                Pour moi et mes proches
                            </p>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-primary" id="filterUpcoming">
                                <i class="ri-calendar-check-line me-1"></i> À venir
                            </button>
                            <button class="btn btn-outline-success" id="filterPast">
                                <i class="ri-history-line me-1"></i> Passés
                            </button>
{{--                            <a href="#" class="btn btn-primary">--}}
{{--                                <i class="ri-calendar-plus-line me-1"></i> Prendre RDV--}}
{{--                            </a>--}}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistiques rapides -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="ri-calendar-line text-primary fs-3"></i>
                            <h3 class="mb-0 mt-2">{{ $rendezVous->count() }}</h3>
                            <p class="text-muted mb-0">Total RDV</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="ri-calendar-check-line text-success fs-3"></i>
                            <h3 class="mb-0 mt-2">
                                {{ $rendezVous->where('date_heure', '>=', now())->count() }}
                            </h3>
                            <p class="text-muted mb-0">À venir</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="ri-time-line text-warning fs-3"></i>
                            <h3 class="mb-0 mt-2">
                                {{ $rendezVous->where('statut', 'planifie')->count() }}
                            </h3>
                            <p class="text-muted mb-0">Planifiés</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="ri-check-double-line text-info fs-3"></i>
                            <h3 class="mb-0 mt-2">
                                {{ $rendezVous->where('statut', 'termine')->count() }}
                            </h3>
                            <p class="text-muted mb-0">Terminés</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Liste des rendez-vous -->
            @if($rendezVous->isEmpty())
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="ri-calendar-line display-1 text-muted"></i>
                        <h5 class="mt-3">Aucun rendez-vous</h5>
                        <p class="text-muted">
                            Vous n'avez pas encore de rendez-vous planifié.
                        </p>
                        <a href="{{ route('patient.rendezvous.create') }}" class="btn btn-primary mt-2">
                            <i class="ri-calendar-plus-line me-1"></i> Prendre mon premier RDV
                        </a>
                    </div>
                </div>
            @else
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle" id="rdvTable">
                                <thead class="table-light">
                                <tr>
                                    <th style="width: 20%;">
                                        <i class="ri-calendar-line me-1"></i>Date & Heure
                                    </th>
                                    <th style="width: 20%;">
                                        <i class="ri-user-line me-1"></i>Patient
                                    </th>
                                    <th style="width: 20%;">
                                        <i class="ri-stethoscope-line me-1"></i>Médecin
                                    </th>
                                    <th style="width: 20%;">
                                        <i class="ri-file-text-line me-1"></i>Motif
                                    </th>
                                    <th style="width: 10%;">
                                        <i class="ri-flag-line me-1"></i>Statut
                                    </th>
                                    <th style="width: 10%;" class="text-end">
                                        Actions
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($rendezVous->sortBy('date_heure') as $rdv)
                                    @php
                                        $isPast = $rdv->date_heure < now();
                                        $rowClass = $isPast ? 'rdv-past' : 'rdv-upcoming';
                                    @endphp
                                    <tr class="{{ $rowClass }}">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-primary-subtle rounded-circle d-flex align-items-center justify-content-center me-3">
                                                    <i class="ri-calendar-line text-primary"></i>
                                                </div>
                                                <div>
                                                    <strong>{{ $rdv->date_heure->format('d/m/Y') }}</strong>
                                                    <br>
                                                    <small class="text-muted">
                                                        <i class="ri-time-line me-1"></i>
                                                        {{ $rdv->date_heure->format('H:i') }}
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-xs bg-info-subtle rounded-circle d-flex align-items-center justify-content-center me-2">
                                                    <span class="text-info fw-bold small">
                                                        {{ strtoupper(substr($rdv->patient->prenom, 0, 1)) }}{{ strtoupper(substr($rdv->patient->nom, 0, 1)) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <strong>{{ $rdv->patient->prenom }} {{ $rdv->patient->nom }}</strong>
                                                    @if($rdv->patient->id == auth()->user()->patient->id)
                                                        <span class="badge bg-primary-subtle text-primary ms-1">Moi</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="ri-user-3-line me-2 text-muted"></i>
                                                Dr. {{ $rdv->medecin->user->name ?? 'N/A' }}
                                            </div>
                                        </td>
                                        <td>
                                            @if($rdv->motif)
                                                <small>{{ Str::limit($rdv->motif, 40) }}</small>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($rdv->statut == 'planifie')
                                                <span class="badge bg-warning text-dark">
                                                    <i class="ri-time-line me-1"></i>Planifié
                                                </span>
                                            @elseif($rdv->statut == 'confirme')
                                                <span class="badge bg-success">
                                                    <i class="ri-check-line me-1"></i>Confirmé
                                                </span>
                                            @elseif($rdv->statut == 'annule')
                                                <span class="badge bg-danger">
                                                    <i class="ri-close-line me-1"></i>Annulé
                                                </span>
                                            @elseif($rdv->statut == 'termine')
                                                <span class="badge bg-info">
                                                    <i class="ri-check-double-line me-1"></i>Terminé
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">{{ $rdv->statut }}</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <div class="btn-group">
                                                <button type="button"
                                                        class="btn btn-sm btn-outline-primary"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#rdvModal{{ $rdv->id }}"
                                                        title="Voir détails">
                                                    <i class="ri-eye-line"></i>
                                                </button>
                                                @if(!$isPast && $rdv->statut != 'annule')
                                                    <button type="button"
                                                            class="btn btn-sm btn-outline-danger"
                                                            onclick="confirmCancel({{ $rdv->id }})"
                                                            title="Annuler">
                                                        <i class="ri-close-line"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Modal détails -->
                                    <div class="modal fade" id="rdvModal{{ $rdv->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Détails du Rendez-vous</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="text-muted small">Patient</label>
                                                        <p class="fw-medium mb-0">{{ $rdv->patient->prenom }} {{ $rdv->patient->nom }}</p>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="text-muted small">Médecin</label>
                                                        <p class="fw-medium mb-0">Dr. {{ $rdv->medecin->user->name ?? 'N/A' }}</p>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="text-muted small">Date & Heure</label>
                                                        <p class="fw-medium mb-0">{{ $rdv->date_heure->format('d/m/Y à H:i') }}</p>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="text-muted small">Motif</label>
                                                        <p class="mb-0">{{ $rdv->motif ?? 'Non spécifié' }}</p>
                                                    </div>
                                                    <div>
                                                        <label class="text-muted small">Statut</label>
                                                        <p class="mb-0">
                                                            <span class="badge bg-{{ $rdv->statut == 'planifie' ? 'warning' : ($rdv->statut == 'confirme' ? 'success' : 'info') }}">
                                                                {{ ucfirst($rdv->statut) }}
                                                            </span>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                                    @if(!$isPast && $rdv->statut != 'annule')
                                                        <button type="button" class="btn btn-danger" onclick="confirmCancel({{ $rdv->id }})">
                                                            Annuler le RDV
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
            // Filtre des rendez-vous
            $('#filterUpcoming').on('click', function() {
                $('.rdv-past').hide();
                $('.rdv-upcoming').show();
                $(this).removeClass('btn-outline-primary').addClass('btn-primary');
                $('#filterPast').removeClass('btn-success').addClass('btn-outline-success');
            });

            $('#filterPast').on('click', function() {
                $('.rdv-upcoming').hide();
                $('.rdv-past').show();
                $(this).removeClass('btn-outline-success').addClass('btn-success');
                $('#filterUpcoming').removeClass('btn-primary').addClass('btn-outline-primary');
            });

            // Fonction d'annulation
            window.confirmCancel = function(rdvId) {
                if (confirm('Êtes-vous sûr de vouloir annuler ce rendez-vous ?')) {
                    // Créer un formulaire d'annulation
                    var form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '/patient/rendezvous/' + rdvId + '/cancel';

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

        .avatar-xs {
            width: 30px;
            height: 30px;
            min-width: 30px;
        }

        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }

        .rdv-past {
            opacity: 0.6;
        }

        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
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
        }
    </style>
@endsection
