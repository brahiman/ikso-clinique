@extends('layouts.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0">Demandes de Consultation à Traiter</h4>
                <a href="{{route('secretaire.consultations.direct')}}" class="btn btn-primary">Nouvelle Demande</a>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Patient</th>
                                <th>Service / Motif</th>
                                <th>Urgence</th>
                                <th>Disponibilités</th>
                                <th>Statut</th>
                                <th>Médecin Assigné</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($demandes as $demande)
                                <tr>
                                    <td>{{ $demande->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <strong>{{ $demande->patient->prenom ?? '' }} {{ $demande->patient->nom ?? '' }}</strong>
                                    </td>
                                    <td>
                                        <strong>{{ $demande->service_souhaite ?? 'Non spécifié' }}</strong><br>
                                        <small>{{ Str::limit($demande->motif ?? '', 60) }}</small>
                                    </td>
                                    <td>
                                        @if($demande->urgence == 'haute')
                                            <span class="badge bg-danger">Urgente</span>
                                        @elseif($demande->urgence == 'moyenne')
                                            <span class="badge bg-warning">Moyenne</span>
                                        @else
                                            <span class="badge bg-info">Normale</span>
                                        @endif
                                    </td>
                                    <td>{{ $demande->disponibilite_patient ?? 'Non précisée' }}</td>
                                    <td>
                                    <span class="badge bg-{{ $demande->statut == 'en_attente' ? 'warning' : 'success' }}">
                                        {{ ucfirst(str_replace('_', ' ', $demande->statut)) }}
                                    </span>
                                    </td>
                                    <td>
                                        @if($demande->medecin)
                                            <span class="text-success">Dr. {{ $demande->medecin->user->name }}</span>
                                        @else
                                            <span class="text-muted">Pas de médecin assigné</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('secretaire.demandes.show', $demande) }}"
                                           class="btn btn-sm btn-info">
                                            <i class="ri-eye-line"></i> Détails
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
