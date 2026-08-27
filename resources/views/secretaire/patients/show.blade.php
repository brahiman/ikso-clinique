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
                                        <a href="{{ route('secretaire.patients.index') }}">Patients</a>
                                    </li>
                                    <li class="breadcrumb-item active">{{ $patient->prenom }} {{ $patient->nom }}</li>
                                </ol>
                            </nav>
                            <h4 class="mb-0">
                                <i class="ri-user-heart-line me-2 text-primary"></i>
                                Détails du Patient
                            </h4>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="" class="btn btn-success">
{{--                                {{ route('secretaire.patients.dossier', $patient) }}--}}
                                <i class="ri-folder-user-line me-1"></i> Dossier médical
                            </a>
                            <a href="{{ route('secretaire.patients.index') }}" class="btn btn-secondary">
                                <i class="ri-arrow-left-line me-1"></i> Retour
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Informations Personnelles -->
                <div class="col-lg-6 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white d-flex align-items-center">
                            <div class="avatar-sm bg-primary-subtle rounded-circle d-flex align-items-center justify-content-center me-3">
                                <i class="ri-user-line text-primary"></i>
                            </div>
                            <h5 class="mb-0">Informations Personnelles</h5>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-4">
                                <div class="avatar-lg bg-primary-subtle rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3">
                                    <span class="text-primary fw-bold fs-3">
                                        {{ strtoupper(substr($patient->prenom, 0, 1)) }}{{ strtoupper(substr($patient->nom, 0, 1)) }}
                                    </span>
                                </div>
                                <h5>{{ $patient->prenom }} {{ $patient->nom }}</h5>
                                <span class="badge bg-primary-subtle text-primary">ID: #{{ $patient->id }}</span>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small mb-1">Nom complet</label>
                                    <p class="fw-medium mb-0">{{ $patient->prenom }} {{ $patient->nom }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small mb-1">Date de naissance</label>
                                    <p class="mb-0">
                                        {{ $patient->date_naissance?->format('d/m/Y') ?? 'Non renseignée' }}
                                        @if($patient->date_naissance)
                                            <small class="text-muted">({{ $patient->date_naissance->age }} ans)</small>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small mb-1">Sexe</label>
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
                                            <span class="text-muted">Non renseigné</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small mb-1">Téléphone</label>
                                    <p class="mb-0">
                                        @if($patient->telephone)
                                            <i class="ri-phone-line me-1 text-muted"></i>{{ $patient->telephone }}
                                        @else
                                            <span class="text-muted">Non renseigné</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small mb-1">Email</label>
                                    <p class="mb-0">
                                        @if($patient->email)
                                            <i class="ri-mail-line me-1 text-muted"></i>{{ $patient->email }}
                                        @else
                                            <span class="text-muted">Non renseigné</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small mb-1">Adresse</label>
                                    <p class="mb-0">
                                        @if($patient->adresse)
                                            <i class="ri-map-pin-line me-1 text-muted"></i>{{ $patient->adresse }}
                                        @else
                                            <span class="text-muted">Non renseignée</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informations Médicales -->
                <div class="col-lg-6 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white d-flex align-items-center">
                            <div class="avatar-sm bg-danger-subtle rounded-circle d-flex align-items-center justify-content-center me-3">
                                <i class="ri-heart-pulse-line text-danger"></i>
                            </div>
                            <h5 class="mb-0">Informations Médicales</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small mb-1">Groupe sanguin</label>
                                    <p class="mb-0">
                                        @if($patient->groupe_sanguin)
                                            <span class="badge bg-danger-subtle text-danger fs-6">
                                                <i class="ri-drop-fill me-1"></i>{{ $patient->groupe_sanguin }}
                                            </span>
                                        @else
                                            <span class="text-muted">Non renseigné</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small mb-1">Contact d'urgence</label>
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
                                            <span class="text-muted">Non renseigné</span>
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <hr class="my-4">

                            <h6 class="fw-bold mb-3">
                                <i class="ri-stethoscope-line me-2 text-primary"></i>
                                Médecins Assignés
                            </h6>
                            @if($patient->medecins->count() > 0)
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($patient->medecins as $med)
                                        <div class="p-2 bg-success-subtle rounded d-flex align-items-center">
                                            <div class="avatar-xs bg-success rounded-circle d-flex align-items-center justify-content-center me-2">
                                                <i class="ri-user-star-line text-white"></i>
                                            </div>
                                            <div>
                                                <span class="fw-medium">Dr. {{ $med->user->name }}</span>
                                                @if($med->specialite)
                                                    <br>
                                                    <small class="text-muted">{{ $med->specialite }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="alert alert-warning mb-0">
                                    <i class="ri-alert-line me-1"></i>
                                    Aucun médecin assigné à ce patient
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistiques rapides -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="ri-calendar-check-line text-primary fs-3"></i>
                            <h3 class="mb-0 mt-2">{{ $patient->rendezVous->count() ?? 0 }}</h3>
                            <p class="text-muted mb-0">Rendez-vous</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="ri-file-medical-line text-success fs-3"></i>
                            <h3 class="mb-0 mt-2">{{ $patient->consultations->count() ?? 0 }}</h3>
                            <p class="text-muted mb-0">Consultations</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="ri-file-list-3-line text-warning fs-3"></i>
                            <h3 class="mb-0 mt-2">{{ $patient->demandesConsultations->count() ?? 0 }}</h3>
                            <p class="text-muted mb-0">Demandes</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="ri-medicine-bottle-line text-info fs-3"></i>
                            <h3 class="mb-0 mt-2">{{ $patient->ordonnances->count() ?? 0 }}</h3>
                            <p class="text-muted mb-0">Ordonnances</p>
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
                                Actions rapides
                            </h6>
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('secretaire.patients.edit', $patient) }}" class="btn btn-warning">
                                    <i class="ri-edit-line me-1"></i> Modifier les informations
                                </a>
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#affecterModal">
                                    <i class="ri-user-add-line me-1"></i> Affecter à un médecin
                                </button>
                                <a href="" class="btn btn-info">
{{--                                    {{ route('secretaire.rendezvous.create', ['patient_id' => $patient->id]) }}--}}
                                    <i class="ri-calendar-plus-line me-1"></i> Planifier un RDV
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Affectation -->
    <div class="modal fade" id="affecterModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">
                        <i class="ri-user-add-line me-2"></i>
                        Affecter {{ $patient->prenom }} {{ $patient->nom }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('secretaire.patients.affecter', $patient) }}" method="POST">
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

                        @if($patient->medecins->count() > 0)
                            <div class="alert alert-info mb-0">
                                <i class="ri-information-line me-1"></i>
                                <strong>Médecins actuels :</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach($patient->medecins as $med)
                                        <li>Dr. {{ $med->user->name }}</li>
                                    @endforeach
                                </ul>
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
@endsection

@section('scripts')
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

        .avatar-xs {
            width: 25px;
            height: 25px;
            min-width: 25px;
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
