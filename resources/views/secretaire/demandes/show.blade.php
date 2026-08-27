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
                                        <a href="{{ route('secretaire.dashboard') }}">
                                            <i class="ri-home-4-line me-1"></i>Accueil
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('secretaire.demandes.index') }}">Demandes</a>
                                    </li>
                                    <li class="breadcrumb-item active">Demande #{{ $demande->id }}</li>
                                </ol>
                            </nav>
                            <h4 class="mb-0">
                                <i class="ri-file-list-3-line me-2 text-primary"></i>
                                Détails de la Demande #{{ $demande->id }}
                            </h4>
                        </div>
                        <div class="d-flex gap-2">
                            @if($demande->statut == 'en_attente')
                                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#affecterModal">
                                    <i class="ri-user-add-line me-1"></i> Affecter
                                </button>
                            @endif
                            <a href="{{ route('secretaire.demandes.index') }}" class="btn btn-secondary">
                                <i class="ri-arrow-left-line me-1"></i> Retour
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statut et urgence -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                                <div class="d-flex gap-3">
                                    <div>
                                        <label class="text-muted small mb-1">Statut</label>
                                        <br>
                                        @if($demande->statut == 'en_attente')
                                            <span class="badge bg-warning text-dark fs-6">
                                                <i class="ri-time-line me-1"></i>En attente
                                            </span>
                                        @elseif($demande->statut == 'acceptee')
                                            <span class="badge bg-success fs-6">
                                                <i class="ri-check-line me-1"></i>Acceptée
                                            </span>
                                        @elseif($demande->statut == 'refusee')
                                            <span class="badge bg-danger fs-6">
                                                <i class="ri-close-line me-1"></i>Refusée
                                            </span>
                                        @elseif($demande->statut == 'en_cours')
                                            <span class="badge bg-primary fs-6">
                                                <i class="ri-loader-line me-1"></i>En cours
                                            </span>
                                        @endif
                                    </div>
                                    <div>
                                        <label class="text-muted small mb-1">Urgence</label>
                                        <br>
                                        @if($demande->urgence == 'haute')
                                            <span class="badge bg-danger fs-6">
                                                <i class="ri-alert-fill me-1"></i>Urgente
                                            </span>
                                        @elseif($demande->urgence == 'moyenne')
                                            <span class="badge bg-warning text-dark fs-6">
                                                <i class="ri-alert-line me-1"></i>Moyenne
                                            </span>
                                        @else
                                            <span class="badge bg-info fs-6">
                                                <i class="ri-information-line me-1"></i>Normale
                                            </span>
                                        @endif
                                    </div>
                                    <div>
                                        <label class="text-muted small mb-1">Date de demande</label>
                                        <br>
                                        <span class="fw-medium">
                                            <i class="ri-calendar-line me-1"></i>
                                            {{ $demande->created_at->format('d/m/Y à H:i') }}
                                        </span>
                                        <br>
                                        <small class="text-muted">{{ $demande->created_at->diffForHumans() }}</small>
                                    </div>
                                    <div>
                                        <label class="text-muted small mb-1">Mode de consultation</label>
                                        <br>
                                        @if(($demande->mode_consultation ?? 'presentiel') === 'distance')
                                            <span class="badge bg-info fs-6">
            <i class="ri-phone-line me-1"></i> À distance
            (téléphone ou vidéo)
        </span>
                                        @else
                                            <span class="badge bg-primary fs-6">
            <i class="ri-hospital-line me-1"></i> Présentiel
        </span>
                                        @endif
                                    </div>
                                </div>
                                @if($demande->medecin)
                                    <div class="text-end">
                                        <label class="text-muted small mb-1">Médecin assigné</label>
                                        <br>
                                        <span class="badge bg-success-subtle text-success fs-6">
                                            <i class="ri-user-star-line me-1"></i>
                                            Dr. {{ $demande->medecin->user->name }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Informations Patient -->
                <div class="col-lg-6 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white d-flex align-items-center">
                            <div class="avatar-sm bg-primary-subtle rounded-circle d-flex align-items-center justify-content-center me-3">
                                <i class="ri-user-line text-primary"></i>
                            </div>
                            <h5 class="mb-0">Informations du Patient</h5>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-4">
                                <div class="avatar-lg bg-primary-subtle rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3">
                                    <span class="text-primary fw-bold fs-3">
                                        {{ strtoupper(substr($demande->patient->prenom, 0, 1)) }}{{ strtoupper(substr($demande->patient->nom, 0, 1)) }}
                                    </span>
                                </div>
                                <h5>{{ $demande->patient->prenom }} {{ $demande->patient->nom }}</h5>
                                <a href="{{ route('secretaire.patients.show', $demande->patient) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="ri-eye-line me-1"></i> Voir le profil complet
                                </a>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small mb-1">Téléphone</label>
                                    <p class="mb-0">
                                        @if($demande->patient->telephone)
                                            <i class="ri-phone-line me-1 text-muted"></i>{{ $demande->patient->telephone }}
                                        @else
                                            <span class="text-muted">Non renseigné</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small mb-1">Email</label>
                                    <p class="mb-0">
                                        @if($demande->patient->email)
                                            <i class="ri-mail-line me-1 text-muted"></i>{{ $demande->patient->email }}
                                        @else
                                            <span class="text-muted">Non renseigné</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small mb-1">Date de naissance</label>
                                    <p class="mb-0">
                                        {{ $demande->patient->date_naissance?->format('d/m/Y') ?? 'Non renseignée' }}
                                        @if($demande->patient->date_naissance)
                                            <small class="text-muted">({{ $demande->patient->date_naissance->age }} ans)</small>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small mb-1">Groupe sanguin</label>
                                    <p class="mb-0">
                                        @if($demande->patient->groupe_sanguin)
                                            <span class="badge bg-danger-subtle text-danger">
                                                <i class="ri-drop-fill me-1"></i>{{ $demande->patient->groupe_sanguin }}
                                            </span>
                                        @else
                                            <span class="text-muted">Non renseigné</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small mb-1">Adresse</label>
                                    <p class="mb-0">
                                        @if($demande->patient->adresse)
                                            <i class="ri-map-pin-line me-1 text-muted"></i>{{ $demande->patient->adresse }}
                                        @else
                                            <span class="text-muted">Non renseignée</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small mb-1">Contact d'urgence</label>
                                    <p class="mb-0">
                                        @if($demande->patient->contact_urgence_nom)
                                            <strong>{{ $demande->patient->contact_urgence_nom }}</strong>
                                            @if($demande->patient->contact_urgence_telephone)
                                                <br>
                                                <small>
                                                    <i class="ri-phone-line me-1 text-muted"></i>
                                                    {{ $demande->patient->contact_urgence_telephone }}
                                                </small>
                                            @endif
                                        @else
                                            <span class="text-muted">Non renseigné</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informations de la Demande -->
                <div class="col-lg-6 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white d-flex align-items-center">
                            <div class="avatar-sm bg-info-subtle rounded-circle d-flex align-items-center justify-content-center me-3">
                                <i class="ri-file-list-3-line text-info"></i>
                            </div>
                            <h5 class="mb-0">Détails de la Demande</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="text-muted small mb-1">Service souhaité</label>
                                <p class="fw-medium mb-0">
                                    <i class="ri-hospital-line me-1 text-muted"></i>
                                    {{ $demande->service_souhaite ?? 'Non spécifié' }}
                                </p>
                            </div>

                            <div class="mb-3">
                                <label class="text-muted small mb-1">Mode de consultation</label>
                                <p class="mb-0">
                                    @if(($demande->mode_consultation ?? 'presentiel') === 'distance')
                                        <span class="badge bg-info">
                <i class="ri-vidicon-line me-1"></i> À distance (téléphone / vidéo)
            </span>
                                        <br>
                                        <small class="text-muted">
                                            Le patient ne peut pas se déplacer à la clinique.
                                        </small>
                                    @else
                                        <span class="badge bg-primary">
                <i class="ri-hospital-line me-1"></i> Présentiel à la clinique
            </span>
                                    @endif
                                </p>
                            </div>

                            <div class="mb-3">
                                <label class="text-muted small mb-1">Motif de consultation</label>
                                <p class="mb-0">{{ $demande->motif }}</p>
                            </div>

                            @if($demande->symptomes)
                                <div class="mb-3">
                                    <label class="text-muted small mb-1">Symptômes</label>
                                    <p class="mb-0">{{ $demande->symptomes }}</p>
                                </div>
                            @endif

                            @if($demande->disponibilite_patient)
                                <div class="mb-3">
                                    <label class="text-muted small mb-1">Disponibilités du patient</label>
                                    <p class="mb-0">
                                        <i class="ri-calendar-line me-1 text-muted"></i>
                                        {{ $demande->disponibilite_patient }}
                                    </p>
                                </div>
                            @endif

                            @if($demande->pref_medecin)
                                <div class="mb-3">
                                    <label class="text-muted small mb-1">Préférence médecin</label>
                                    <p class="mb-0">
                                        <i class="ri-user-star-line me-1 text-muted"></i>
                                        {{ $demande->pref_medecin }}
                                    </p>
                                </div>
                            @endif

                            @if($demande->medecin)
                                <div class="mb-3">
                                    <label class="text-muted small mb-1">Médecin assigné</label>
                                    <p class="mb-0">
                                        <span class="badge bg-success-subtle text-success">
                                            <i class="ri-user-star-line me-1"></i>
                                            Dr. {{ $demande->medecin->user->name }}
                                            @if($demande->medecin->specialite)
                                                - {{ $demande->medecin->specialite }}
                                            @endif
                                        </span>
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h6 class="fw-bold mb-3">
                                <i class="ri-flashlight-line me-2 text-warning"></i>
                                Actions
                            </h6>
                            <div class="d-flex flex-wrap gap-2">
                                @if($demande->statut == 'en_attente')
                                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#affecterModal">
                                        <i class="ri-user-add-line me-1"></i> Affecter à un médecin
                                    </button>
                                    <button class="btn btn-danger" onclick="confirmRefus({{ $demande->id }})">
                                        <i class="ri-close-line me-1"></i> Refuser la demande
                                    </button>
                                @endif
{{--                                <a href="{{ route('secretaire.patients.dossier', $demande->patient) }}" class="btn btn-info">--}}
{{--                                    <i class="ri-folder-user-line me-1"></i> Voir le dossier médical--}}
{{--                                </a>--}}
{{--                                <a href="{{ route('secretaire.rendezvous.create', ['patient_id' => $demande->patient->id]) }}" class="btn btn-primary">--}}
{{--                                    <i class="ri-calendar-plus-line me-1"></i> Planifier un RDV--}}
{{--                                </a>--}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Affectation -->
    @if($demande->statut == 'en_attente')
        <div class="modal fade" id="affecterModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title">
                            <i class="ri-user-add-line me-2"></i>
                            Affecter la demande à un médecin
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('secretaire.demandes.affecter', $demande) }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="fw-bold">Choisir un médecin</label>

                                <select name="medecin_id" class="form-select" required>
                                    <option value="">-- Sélectionner un médecin --</option>
                                    @foreach(\App\Models\Medecin::with('user')->get() as $med)
                                        <option value="{{ $med->id }}">
                                            Dr. {{ $med->user->name }}
                                            @if($med->specialite)
                                                - {{ $med->specialite }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            @if($demande->pref_medecin)
                                <div class="alert alert-info mb-0">
                                    <i class="ri-information-line me-1"></i>
                                    <strong>Préférence du patient :</strong> {{ $demande->pref_medecin }}
                                </div>
                            @endif

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="ri-close-line me-1"></i> Annuler
                            </button>
                            <button type="submit" class="btn btn-success">
                                <i class="ri-check-line me-1"></i> Affecter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Confirmation de refus
            window.confirmRefus = function(demandeId) {
                Swal.fire({
                    title: 'Refuser la demande ?',
                    text: "Cette action est irréversible !",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Oui, refuser',
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (result.isConfirmed) {
                        var form = document.createElement('form');
                        form.method = 'POST';
                        form.action = '/secretaire/demandes/' + demandeId + '/refuser';

                        var csrfInput = document.createElement('input');
                        csrfInput.type = 'hidden';
                        csrfInput.name = '_token';
                        csrfInput.value = '{{ csrf_token() }}';

                        form.appendChild(csrfInput);
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            };
        });
    </script>

    <style>
        .avatar-sm {
            width: 40px;
            height: 40px;
            min-width: 40px;
        }

        .avatar-lg {
            width: 80px;
            height: 80px;
            min-width: 80px;
        }

        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
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
