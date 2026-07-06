@extends('layouts.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <h2 class="mb-4">Accueil Secrétaire</h2>

            <div class="row">

                <!-- Demandes en attente -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header bg-warning text-white">
                            <h5>Demandes en Attente ({{ $demandesEnAttente->count() }})</h5>
                        </div>
                        <div class="card-body">
                            @if($demandesEnAttente->isEmpty())
                                <p>Aucune demande en attente.</p>
                            @else
                                <ul class="list-group">
                                    @foreach($demandesEnAttente as $demande)
                                        <li class="list-group-item d-flex justify-content-between">
                                            <div>
                                                <strong>{{ $demande->patient->prenom }} {{ $demande->patient->nom }}</strong><br>
                                                <small>{{ $demande->motif }}</small>
                                            </div>
                                            <a href="#" class="btn btn-sm btn-primary">Affecter</a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Rendez-vous du jour -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <h5>Rendez-vous Aujourd'hui</h5>
                        </div>
                        <div class="card-body">
                            @if($rendezVousAujourdhui->isEmpty())
                                <p>Aucun rendez-vous aujourd'hui.</p>
                            @else
                                <ul class="list-group">
                                    @foreach($rendezVousAujourdhui as $rdv)
                                        <li class="list-group-item">
                                            {{ $rdv->date_heure->format('H:i') }} -
                                            <strong>{{ $rdv->patient->prenom }} {{ $rdv->patient->nom }}</strong>
                                            <span class="float-end">Dr. {{ $rdv->medecin->user->name }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>

            </div>

            <!-- Liens rapides -->

            <div class="row mt-4">
                <div class="col-md-4">
                    <a href="{{ route('secretaire.patients.index') }}" class="btn btn-outline-primary w-100 py-3">
                        <i class="ri-user-line"></i><br>Gérer les Patients
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('secretaire.demandes.index') }}" class="btn btn-outline-success w-100 py-3">
                        <i class="ri-file-list-line"></i><br>Voir toutes les Demandes
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="#" class="btn btn-outline-info w-100 py-3">
                        <i class="ri-calendar-line"></i><br>Gérer les Rendez-vous
                    </a>
                </div>
            </div>

        </div>
    </div>
@endsection
