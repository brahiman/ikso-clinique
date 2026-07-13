@extends('layouts.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <h2 class="mb-4">Mon Tableau de bord - Dr. {{ auth()->user()->name }}</h2>

            <div class="row">
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-body">
                            <h5>Patients suivis</h5>
                            <h3 class="text-primary">{{ $nombrePatients }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-body">
                            <h5>Rendez-vous aujourd'hui</h5>
                            <h3 class="text-success">{{ $rendezVousAujourdhui->count() }}</h3>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row mt-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h5>Mes rendez-vous du jour</h5>
                            @forelse($rendezVousAujourdhui as $rdv)
                                <div class="d-flex justify-content-between border-bottom py-2">
                                    <span>{{ $rdv->date_heure->format('H:i') }} — {{ $rdv->patient->prenom }} {{ $rdv->patient->nom }}</span>
                                    <span class="badge bg-secondary">{{ $rdv->statut }}</span>
                                </div>
                            @empty
                                <p class="text-muted mb-0">Aucun rendez-vous aujourd'hui.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 class="mb-0">Demandes en attente de confirmation ({{ $nombreDemandes }})</h5>
                                <a href="{{ route('medecin.demandes.index') }}" class="btn btn-sm btn-outline-primary">Voir plus</a>
                            </div>
                            @forelse($demandeConsultations as $demande)
                                <div class="d-flex justify-content-between border-bottom py-2">
                                    <span>{{ $demande->patient->prenom }} {{ $demande->patient->nom }} — {{ $demande->service_souhaite }}</span>
                                    <span class="badge bg-{{ $demande->urgence === 'haute' ? 'danger' : ($demande->urgence === 'moyenne' ? 'warning' : 'secondary') }}">
                            {{ $demande->urgence }}
                        </span>
                                </div>
                            @empty
                                <p class="text-muted mb-0">Aucune demande en attente.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
