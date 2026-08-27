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
                                <i class="ri-file-medical-line me-2 text-primary"></i>
                                Mes Consultations
                            </h4>
                            <p class="text-muted mb-0">
                                <i class="ri-team-line me-1"></i>
                                Pour moi et mes proches
                            </p>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-primary" id="filterRecent">
                                <i class="ri-time-line me-1"></i> Récentes
                            </button>
                            <button class="btn btn-outline-success" id="filterAll">
                                <i class="ri-list-check me-1"></i> Toutes
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistiques rapides -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="ri-file-medical-line text-primary fs-3"></i>
                            <h3 class="mb-0 mt-2">{{ $consultations->count() }}</h3>
                            <p class="text-muted mb-0">Total consultations</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="ri-check-double-line text-success fs-3"></i>
                            <h3 class="mb-0 mt-2">
                                {{ $consultations->where('statut', 'terminee')->count() }}
                            </h3>
                            <p class="text-muted mb-0">Terminées</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="ri-time-line text-warning fs-3"></i>
                            <h3 class="mb-0 mt-2">
                                {{ $consultations->where('statut', 'en_cours')->count() }}
                            </h3>
                            <p class="text-muted mb-0">En cours</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="ri-user-heart-line text-info fs-3"></i>
                            <h3 class="mb-0 mt-2">
                                {{ $consultations->unique('medecin_id')->count() }}
                            </h3>
                            <p class="text-muted mb-0">Médecins consultés</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Liste des consultations -->
            @if($consultations->isEmpty())
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="ri-file-search-line display-1 text-muted"></i>
                        <h5 class="mt-3">Aucune consultation</h5>
                        <p class="text-muted">
                            Vous n'avez pas encore de consultation enregistrée.
                        </p>
                    </div>
                </div>
            @else
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle" id="consultationsTable">
                                <thead class="table-light">
                                <tr>
                                    <th style="width: 15%;">
                                        <i class="ri-calendar-line me-1"></i>Date
                                    </th>
                                    <th style="width: 20%;">
                                        <i class="ri-user-line me-1"></i>Patient
                                    </th>
                                    <th style="width: 18%;">
                                        <i class="ri-stethoscope-line me-1"></i>Médecin
                                    </th>
                                    <th style="width: 25%;">
                                        <i class="ri-file-text-line me-1"></i>Diagnostic
                                    </th>
                                    <th style="width: 12%;">
                                        <i class="ri-flag-line me-1"></i>Statut
                                    </th>
                                    <th style="width: 10%;" class="text-end">
                                        Actions
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($consultations->sortByDesc('date_consultation') as $consult)
                                    @php
                                        $isRecent = $consult->date_consultation->diffInDays(now()) <= 30;
                                        $rowClass = $isRecent ? 'consult-recent' : 'consult-old';
                                    @endphp
                                    <tr class="{{ $rowClass }}">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-primary-subtle rounded-circle d-flex align-items-center justify-content-center me-3">
                                                    <i class="ri-calendar-line text-primary"></i>
                                                </div>
                                                <div>
                                                    <strong>{{ $consult->date_consultation->format('d/m/Y') }}</strong>
                                                    <br>
                                                    <small class="text-muted">
                                                        {{ $consult->date_consultation->format('H:i') }}
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-xs bg-info-subtle rounded-circle d-flex align-items-center justify-content-center me-2">
                                                    <span class="text-info fw-bold small">
                                                        {{ strtoupper(substr($consult->patient->prenom, 0, 1)) }}{{ strtoupper(substr($consult->patient->nom, 0, 1)) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <strong>{{ $consult->patient->prenom }} {{ $consult->patient->nom }}</strong>
                                                    @if(isset($consult->patient) && auth()->user()->patient && $consult->patient->id == auth()->user()->patient->id)
                                                        <span class="badge bg-primary-subtle text-primary ms-1">Moi</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="ri-user-3-line me-2 text-muted"></i>
                                                Dr. {{ $consult->medecin->user->name ?? 'N/A' }}
                                            </div>
                                        </td>
                                        <td>
                                            @if($consult->diagnostic)
                                                <span class="badge bg-danger-subtle text-danger mb-1">
                                                    {{ Str::limit($consult->diagnostic, 50) }}
                                                </span>
                                                @if($consult->observations)
                                                    <br>
                                                    <small class="text-muted">
                                                        <i class="ri-chat-3-line me-1"></i>
                                                        {{ Str::limit($consult->observations, 40) }}
                                                    </small>
                                                @endif
                                            @else
                                                <span class="text-muted">
                                                    <i class="ri-time-line me-1"></i>En attente de diagnostic
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($consult->statut == 'terminee')
                                                <span class="badge bg-success">
                                                    <i class="ri-check-line me-1"></i>Terminée
                                                </span>
                                            @elseif($consult->statut == 'en_cours')
                                                <span class="badge bg-warning text-dark">
                                                    <i class="ri-time-line me-1"></i>En cours
                                                </span>
                                            @elseif($consult->statut == 'planifiee')
                                                <span class="badge bg-info">
                                                    <i class="ri-calendar-line me-1"></i>Planifiée
                                                </span>
                                            @elseif($consult->statut == 'annulee')
                                                <span class="badge bg-danger">
                                                    <i class="ri-close-line me-1"></i>Annulée
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">{{ $consult->statut }}</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <div class="btn-group">
                                                <button type="button"
                                                        class="btn btn-sm btn-outline-primary"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#consultModal{{ $consult->id }}"
                                                        title="Voir détails">
                                                    <i class="ri-eye-line"></i>
                                                </button>
                                                @if($consult->prescriptions && $consult->prescriptions->count() > 0)
                                                    <a href="{{ route('patient.ordonnances.show', $consult->id) }}"
                                                       class="btn btn-sm btn-outline-success"
                                                       title="Voir ordonnances">
                                                        <i class="ri-medicine-bottle-line"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Modal détails -->
                                    <div class="modal fade" id="consultModal{{ $consult->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header bg-primary text-white">
                                                    <h5 class="modal-title">
                                                        <i class="ri-file-medical-line me-2"></i>
                                                        Détails de la Consultation
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label class="text-muted small">Patient</label>
                                                            <p class="fw-medium mb-0">
                                                                {{ $consult->patient->prenom }} {{ $consult->patient->nom }}
                                                            </p>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="text-muted small">Médecin</label>
                                                            <p class="fw-medium mb-0">
                                                                Dr. {{ $consult->medecin->user->name ?? 'N/A' }}
                                                            </p>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="text-muted small">Date</label>
                                                            <p class="fw-medium mb-0">
                                                                {{ $consult->date_consultation->format('d/m/Y à H:i') }}
                                                            </p>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="text-muted small">Statut</label>
                                                            <p class="mb-0">
                                                                <span class="badge bg-{{ $consult->statut == 'terminee' ? 'success' : 'warning' }}">
                                                                    {{ ucfirst($consult->statut) }}
                                                                </span>
                                                            </p>
                                                        </div>
                                                        <div class="col-12 mb-3">
                                                            <label class="text-muted small">Diagnostic</label>
                                                            <p class="mb-0">
                                                                {{ $consult->diagnostic ?? 'En attente de diagnostic' }}
                                                            </p>
                                                        </div>
                                                        <div class="col-12">
                                                            <label class="text-muted small">Observations</label>
                                                            <p class="mb-0">
                                                                {{ $consult->observations ?? 'Aucune observation' }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                                    @if($consult->prescriptions && $consult->prescriptions->count() > 0)
                                                        <a href="{{ route('patient.ordonnances.show', $consult->id) }}" class="btn btn-primary">
                                                            <i class="ri-medicine-bottle-line me-1"></i> Voir les ordonnances
                                                        </a>
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
            // Filtre des consultations
            $('#filterRecent').on('click', function() {
                $('.consult-old').hide();
                $('.consult-recent').show();
                $(this).removeClass('btn-outline-primary').addClass('btn-primary');
                $('#filterAll').removeClass('btn-success').addClass('btn-outline-success');
            });

            $('#filterAll').on('click', function() {
                $('.consult-old').show();
                $('.consult-recent').show();
                $(this).removeClass('btn-outline-success').addClass('btn-success');
                $('#filterRecent').removeClass('btn-primary').addClass('btn-outline-primary');
            });

            // Recherche dans le tableau (optionnel)
            $('#searchConsultation').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $('#consultationsTable tbody tr').each(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });
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

        .consult-old {
            opacity: 0.6;
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

            .modal-lg {
                max-width: 100%;
            }
        }
    </style>
@endsection
