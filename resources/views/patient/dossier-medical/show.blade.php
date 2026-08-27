@extends('layouts.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <!-- Header -->
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
                                        <a href="{{ route('patient.dossier.index') }}">Dossiers Médicaux</a>
                                    </li>
                                    <li class="breadcrumb-item active">{{ $patient->prenom }} {{ $patient->nom }}</li>
                                </ol>
                            </nav>
                            <h4 class="mb-0">
                                <i class="ri-folder-user-line me-2 text-primary"></i>
                                Dossier Médical - {{ $patient->prenom }} {{ $patient->nom }}
                            </h4>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-primary" onclick="window.print()">
                                <i class="ri-printer-line me-1"></i> Imprimer
                            </button>
                            <a href="{{ route('patient.dossier.index') }}" class="btn btn-secondary">
                                <i class="ri-arrow-left-line me-1"></i> Retour
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informations Générales -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white d-flex align-items-center">
                            <i class="ri-user-line me-2 text-primary"></i>
                            <h5 class="mb-0">Informations Générales</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-3 col-sm-6">
                                    <div class="p-3 bg-light rounded">
                                        <label class="text-muted small mb-1">Date de naissance</label>
                                        <p class="mb-0 fw-medium">
                                            <i class="ri-calendar-line me-1 text-muted"></i>
                                            {{ $patient->date_naissance?->format('d/m/Y') ?? 'Non renseignée' }}
                                            @if($patient->date_naissance)
                                                <small class="text-muted">({{ $patient->date_naissance->age }} ans)</small>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="p-3 bg-light rounded">
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
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="p-3 bg-light rounded">
                                        <label class="text-muted small mb-1">Téléphone</label>
                                        <p class="mb-0 fw-medium">
                                            <i class="ri-phone-line me-1 text-muted"></i>
                                            {{ $patient->telephone ?? 'Non renseigné' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="p-3 bg-light rounded">
                                        <label class="text-muted small mb-1">Adresse</label>
                                        <p class="mb-0 fw-medium">
                                            <i class="ri-map-pin-line me-1 text-muted"></i>
                                            {{ $patient->adresse ?? 'Non renseignée' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Antécédents Médicaux -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <i class="ri-health-book-line me-2 text-warning"></i>
                                <h5 class="mb-0">Antécédents Médicaux</h5>
                            </div>
                            <span class="badge bg-warning-subtle text-warning">
                                {{ $patient->antecedents->count() ?? 0 }}
                            </span>
                        </div>
                        <div class="card-body">
                            @if($patient->antecedents->isEmpty())
                                <div class="text-center py-4">
                                    <i class="ri-health-book-line fs-1 text-muted"></i>
                                    <p class="text-muted mb-0">Aucun antécédent déclaré</p>
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="table-light">
                                        <tr>
                                            <th>Type</th>
                                            <th>Nom</th>
                                            <th>Description</th>
                                            <th>Date</th>
                                            <th>Gravité</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($patient->antecedents as $antecedent)
                                            <tr>
                                                <td>
                                                    @if($antecedent->type == 'allergie')
                                                        <span class="badge bg-danger-subtle text-danger">
                                                            <i class="ri-alert-line me-1"></i>Allergie
                                                        </span>
                                                    @elseif($antecedent->type == 'maladie')
                                                        <span class="badge bg-primary-subtle text-primary">
                                                            <i class="ri-virus-line me-1"></i>Maladie
                                                        </span>
                                                    @elseif($antecedent->type == 'chirurgie')
                                                        <span class="badge bg-info-subtle text-info">
                                                            <i class="ri-scissors-line me-1"></i>Chirurgie
                                                        </span>
                                                    @else
                                                        <span class="badge bg-secondary">{{ $antecedent->type }}</span>
                                                    @endif
                                                </td>
                                                <td><strong>{{ $antecedent->nom }}</strong></td>
                                                <td>{{ Str::limit($antecedent->description ?? '—', 50) }}</td>
                                                <td>{{ $antecedent->date_evenement?->format('d/m/Y') ?? '—' }}</td>
                                                <td>
                                                    @if($antecedent->gravite == 'haute')
                                                        <span class="badge bg-danger">Haute</span>
                                                    @elseif($antecedent->gravite == 'moyenne')
                                                        <span class="badge bg-warning text-dark">Moyenne</span>
                                                    @elseif($antecedent->gravite == 'basse')
                                                        <span class="badge bg-success">Basse</span>
                                                    @else
                                                        <span class="text-muted">—</span>
                                                    @endif
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

            <!-- Examens Complémentaires -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <i class="ri-microscope-line me-2 text-info"></i>
                                <h5 class="mb-0">Examens Complémentaires</h5>
                            </div>
                            <span class="badge bg-info-subtle text-info">
                                {{ $patient->demandesExamens->count() ?? 0 }}
                            </span>
                        </div>
                        <div class="card-body">
                            @if($patient->demandesExamens->isEmpty())
                                <div class="text-center py-4">
                                    <i class="ri-microscope-line fs-1 text-muted"></i>
                                    <p class="text-muted mb-0">Aucun examen enregistré</p>
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="table-light">
                                        <tr>
                                            <th>Type</th>
                                            <th>Description</th>
                                            <th>Date Demande</th>
                                            <th>Résultat</th>
                                            <th>Statut</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($patient->demandesExamens as $examen)
                                            <tr>
                                                <td>
                                                    <strong>{{ $examen->type_examen }}</strong>
                                                </td>
                                                <td>{{ Str::limit($examen->description, 50) }}</td>
                                                <td>{{ $examen->date_demande->format('d/m/Y') }}</td>
                                                <td>
                                                    @if($examen->resultats)
                                                        <span class="text-success">
                                                            <i class="ri-check-line me-1"></i>
                                                            {{ Str::limit($examen->resultats, 40) }}
                                                        </span>
                                                    @else
                                                        <span class="text-muted">—</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($examen->resultats)
                                                        <span class="badge bg-success">Terminé</span>
                                                    @else
                                                        <span class="badge bg-warning text-dark">
                                                            <i class="ri-time-line me-1"></i>En attente
                                                        </span>
                                                    @endif
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

            <!-- Traitements & Ordonnances -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <i class="ri-medicine-bottle-line me-2 text-success"></i>
                                <h5 class="mb-0">Traitements & Ordonnances</h5>
                            </div>
                            <span class="badge bg-success-subtle text-success">
                                {{ $patient->ordonnances->count() ?? 0 }}
                            </span>
                        </div>
                        <div class="card-body">
                            @php
                                $ordonnancesByConsultation = $patient->ordonnances->groupBy('consultation_id');
                            @endphp

                            @if($patient->ordonnances->isEmpty())
                                <div class="text-center py-4">
                                    <i class="ri-medicine-bottle-line fs-1 text-muted"></i>
                                    <p class="text-muted mb-0">Aucune ordonnance enregistrée</p>
                                </div>
                            @else
                                @foreach($ordonnancesByConsultation as $consultationId => $ordonnances)
                                    @php
                                        $firstOrd = $ordonnances->first();
                                        $consultation = $firstOrd->consultation;
                                    @endphp

                                    <div class="mb-4">
                                        <div class="d-flex justify-content-between align-items-center mb-3 p-2 bg-light rounded">
                                            <div>
                                                <strong>
                                                    <i class="ri-calendar-check-line me-1"></i>
                                                    Consultation du
                                                    {{ $consultation?->date_consultation?->format('d/m/Y') ?? $firstOrd->date_prescription->format('d/m/Y') }}
                                                </strong>
                                                <br>
                                                <small class="text-muted">
                                                    Dr. {{ $firstOrd->medecin->user->name ?? 'N/A' }}
                                                </small>
                                            </div>
                                            @if($consultation?->diagnostic)
                                                <div class="text-end">
                                                    <small class="text-muted d-block">Diagnostic :</small>
                                                    <span class="badge bg-primary-subtle text-primary">
                                                        {{ $consultation->diagnostic }}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover align-middle mb-0">
                                                <thead class="table-light">
                                                <tr>
                                                    <th>Médicament</th>
                                                    <th>Dosage</th>
                                                    <th>Quantité</th>
                                                    <th>Fréquence</th>
                                                    <th>Moment</th>
                                                    <th>Durée</th>
                                                    <th>Instructions</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($ordonnances as $ord)
                                                    @foreach($ord->details as $detail)
                                                        <tr>
                                                            <td>
                                                                <strong>{{ $detail->medicament->nom ?? '—' }}</strong>
                                                            </td>
                                                            <td>{{ $detail->dosage_prescrit ?? '—' }}</td>
                                                            <td>{{ $detail->quantite ?? '—' }}</td>
                                                            <td>{{ $detail->frequence ?? '—' }}</td>
                                                            <td>
                                                                @if($detail->moment == 'matin')
                                                                    <span class="badge bg-warning-subtle text-warning">🌅 Matin</span>
                                                                @elseif($detail->moment == 'midi')
                                                                    <span class="badge bg-info-subtle text-info">☀️ Midi</span>
                                                                @elseif($detail->moment == 'soir')
                                                                    <span class="badge bg-primary-subtle text-primary">🌙 Soir</span>
                                                                @else
                                                                    {{ $detail->moment ?? '—' }}
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if($detail->duree_jours)
                                                                    <span class="badge bg-success-subtle text-success">
                                                                        {{ $detail->duree_jours }} jours
                                                                    </span>
                                                                @else
                                                                    —
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <small>{{ Str::limit($detail->instructions ?? '—', 40) }}</small>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    @if(!$loop->last)
                                        <hr class="my-4">
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Historique des Consultations -->
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <i class="ri-file-history-line me-2 text-primary"></i>
                                <h5 class="mb-0">Historique des Consultations</h5>
                            </div>
                            <span class="badge bg-primary-subtle text-primary">
                                {{ $patient->consultations->count() ?? 0 }}
                            </span>
                        </div>
                        <div class="card-body">
                            @if($patient->consultations->isEmpty())
                                <div class="text-center py-4">
                                    <i class="ri-file-history-line fs-1 text-muted"></i>
                                    <p class="text-muted mb-0">Aucune consultation enregistrée</p>
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="table-light">
                                        <tr>
                                            <th>Date</th>
                                            <th>Médecin</th>
                                            <th>Diagnostic</th>
                                            <th>Observations</th>
                                            <th>Documents</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($patient->consultations->sortByDesc('date_consultation') as $consult)
                                            <tr>
                                                <td>
                                                    <strong>{{ $consult->date_consultation->format('d/m/Y') }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $consult->date_consultation->format('H:i') }}</small>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <i class="ri-user-3-line me-2 text-muted"></i>
                                                        Dr. {{ $consult->medecin->user->name ?? 'N/A' }}
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($consult->diagnostic)
                                                        <span class="badge bg-danger-subtle text-danger">
                                                            {{ Str::limit($consult->diagnostic, 50) }}
                                                        </span>
                                                    @else
                                                        <span class="text-muted">—</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <small>{{ Str::limit($consult->observations ?? '—', 80) }}</small>
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary" title="Voir documents">
                                                        <i class="ri-attachment-2"></i>
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
    <style>
        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
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

        .badge {
            font-weight: 500;
        }

        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
        }

        @media print {
            .btn, .breadcrumb, .card-header .badge {
                display: none !important;
            }

            .card {
                box-shadow: none !important;
                border: 1px solid #dee2e6 !important;
            }
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
