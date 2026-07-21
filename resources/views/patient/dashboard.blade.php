@extends('layouts.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <!-- Header -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Bienvenue, {{ auth()->user()->name }}</h4>
                        <div class="page-title-right">
                            <span class="badge bg-success">Patient</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">

                <!-- Stats Cards -->
                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="ri-calendar-check-line display-4 text-primary"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h4 class="mb-0">{{ $rendezVousAvenir ?? 0 }}</h4>
                                    <p class="text-muted">Rendez-vous à venir</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="ri-file-medical-line display-4 text-success"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h4 class="mb-0">{{ $consultations ?? 0 }}</h4>
                                    <p class="text-muted">Consultations</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="ri-file-list-3-line display-4 text-warning"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h4 class="mb-0">{{ $demandesEnAttente ?? 0 }}</h4>
                                    <p class="text-muted">Demandes en attente</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="ri-user-heart-line display-4 text-info"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h4 class="mb-0">{{ $medecinsAssignes ?? 0 }}</h4>
                                    <p class="text-muted">Médecins assignés</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Prochains Rendez-vous -->
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h5>Prochains Rendez-vous</h5>
                        </div>
                        <div class="card-body">
                            @if(($prochainsRdv ?? collect())->isEmpty())
                                <p class="text-muted">Aucun rendez-vous programmé.</p>
                            @else
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Heure</th>
                                            <th>Médecin</th>
                                            <th>Motif</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($prochainsRdv as $rdv)
                                            <tr>
                                                <td>{{ $rdv->date_heure->format('d/m/Y') }}</td>
                                                <td>{{ $rdv->date_heure->format('H:i') }}</td>
                                                <td>Dr. {{ $rdv->medecin->user->name }}</td>
                                                <td>{{ $rdv->motif }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Dernières Consultations -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>Dernières Consultations</h5>
                        </div>
                        <div class="card-body">
                            @if(($dernieresConsultations ?? collect())->isEmpty())

                                <p class="text-muted">Aucune consultation récente.</p>
                            @else
                                <ul class="list-group">
                                    @foreach($dernieresConsultations as $consult)
                                        <li class="list-group-item">
                                            {{ $consult->date_consultation->format('d/m/Y') }} -
                                            Dr. {{ $consult->medecin->user->name }}
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
