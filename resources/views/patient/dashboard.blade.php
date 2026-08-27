@extends('layouts.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- Header -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="mb-1">Bienvenue, {{ auth()->user()->name }} 👋</h4>
                            <p class="text-muted mb-0">
                                <i class="ri-calendar-line me-1"></i>
                                {{ now()->locale('fr')->isoFormat('dddd D MMMM YYYY') }}
                            </p>
                        </div>
                        <div>
                        <span class="badge bg-success fs-6">
                            <i class="ri-user-heart-line me-1"></i> Patient / Responsable
                        </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="row">
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar-sm bg-primary-subtle rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="ri-calendar-check-line fs-3 text-primary"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h3 class="mb-1">{{ $rendezVousAvenir ?? 0 }}</h3>
                                    <p class="text-muted mb-0">Rendez-vous à venir</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar-sm bg-success-subtle rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="ri-file-medical-line fs-3 text-success"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h3 class="mb-1">{{ $consultations ?? 0 }}</h3>
                                    <p class="text-muted mb-0">Consultations</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar-sm bg-warning-subtle rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="ri-file-list-3-line fs-3 text-warning"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h3 class="mb-1">{{ $demandesEnAttente ?? 0 }}</h3>
                                    <p class="text-muted mb-0">Demandes en attente</p>
                                </div>
                                <a href="{{ route('patient.demandes.index') }}" class="text-muted">
                                    <i class="ri-arrow-right-s-line fs-4"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar-sm bg-info-subtle rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="ri-user-heart-line fs-3 text-info"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h3 class="mb-1">{{ $medecinsAssignes ?? 0 }}</h3>
                                    <p class="text-muted mb-0">Médecins assignés</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mes patients (moi + enfants) -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="ri-group-line me-2 text-primary"></i>
                                Mes patients
                            </h5>
                            <a href="{{ route('patient.patients.index') }}" class="btn btn-sm btn-outline-primary">
                                Gérer
                            </a>
                        </div>
                        <div class="card-body">
                            @if(($patients ?? collect())->isEmpty())
                                <p class="text-muted mb-0">Aucun patient associé à votre compte.</p>
                            @else
                                <div class="row">
                                    @foreach($patients as $patient)
                                        <div class="col-md-4 mb-3">
                                            <div class="border rounded p-3 h-100">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div>
                                                        <h6 class="mb-1">{{ $patient->prenom }} {{ $patient->nom }}</h6>
                                                        <small class="text-muted">
                                                            {{ $patient->date_naissance?->format('d/m/Y') ?? '—' }}
                                                            @if($patient->telephone)
                                                                · {{ $patient->telephone }}
                                                            @endif
                                                        </small>
                                                    </div>
                                                    <span class="badge bg-light text-dark">
                                                    {{ $patient->groupe_sanguin ?? '—' }}
                                                </span>
                                                </div>
                                                <div class="mt-3 d-flex gap-2 flex-wrap">
                                                    <a href="{{ route('patient.patients.show', $patient) }}"
                                                       class="btn btn-sm btn-outline-info">Voir</a>
                                                    <a href="{{ route('patient.patients.edit', $patient) }}"
                                                       class="btn btn-sm btn-outline-warning">Modifier</a>
                                                    <a href="{{ route('patient.demandes.create-for-patient', $patient) }}"
                                                       class="btn btn-sm btn-primary">Demande</a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Prochains Rendez-vous + Dernières Consultations -->
            <div class="row">
                <div class="col-lg-8 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="ri-calendar-todo-line me-2 text-primary"></i>
                                Prochains Rendez-vous
                            </h5>
                            <a href="#" class="btn btn-sm btn-outline-primary">
                                Voir tout
                            </a>
                        </div>
                        <div class="card-body">
                            @if(($prochainsRdv ?? collect())->isEmpty())
                                <div class="text-center py-4">
                                    <i class="ri-calendar-line fs-1 text-muted"></i>
                                    <p class="text-muted mb-0">Aucun rendez-vous programmé</p>
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="table-light">
                                        <tr>
                                            <th>Patient</th>
                                            <th>Date</th>
                                            <th>Heure</th>
                                            <th>Médecin</th>
                                            <th>Motif</th>
                                            <th>Statut</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($prochainsRdv->take(3) as $rdv)
                                            <tr>
                                                <td>
                                                    <strong>{{ $rdv->patient->prenom ?? '' }} {{ $rdv->patient->nom ?? '' }}</strong>
                                                </td>
                                                <td>{{ $rdv->date_heure->format('d/m/Y') }}</td>
                                                <td>{{ $rdv->date_heure->format('H:i') }}</td>
                                                <td>Dr. {{ $rdv->medecin->user->name ?? 'N/A' }}</td>
                                                <td>{{ Str::limit($rdv->motif ?? '—', 30) }}</td>
                                                <td>
                                                    <span class="badge bg-success-subtle text-success">
                                                        {{ ucfirst($rdv->statut ?? 'Confirmé') }}
                                                    </span>
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

                <div class="col-lg-4 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="ri-file-history-line me-2 text-success"></i>
                                Dernières Consultations
                            </h5>
                            <a href="{{ route('patient.consultations') }}" class="btn btn-sm btn-outline-success">
                                Voir tout
                            </a>
                        </div>
                        <div class="card-body">
                            @if(($dernieresConsultations ?? collect())->isEmpty())
                                <div class="text-center py-4">
                                    <i class="ri-file-search-line fs-1 text-muted"></i>
                                    <p class="text-muted mb-0">Aucune consultation récente</p>
                                </div>
                            @else
                                <div class="list-group list-group-flush">
                                    @foreach($dernieresConsultations->take(3) as $consult)
                                        <div class="list-group-item px-0 d-flex align-items-center">
                                            <div class="avatar-xs bg-primary-subtle rounded-circle d-flex align-items-center justify-content-center me-3">
                                                <i class="ri-file-text-line text-primary"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <p class="mb-0 fw-medium">
                                                    {{ $consult->patient->prenom ?? '' }} {{ $consult->patient->nom ?? '' }}
                                                </p>
                                                <small class="text-muted">
                                                    {{ $consult->date_consultation->format('d/m/Y') }}
                                                    · Dr. {{ $consult->medecin->user->name ?? 'N/A' }}
                                                </small>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
