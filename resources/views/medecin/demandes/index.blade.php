@extends('layouts.master')

@section('content')
<div class="page-content">
    <div class="container-fluid">
        <h2 class="mb-4">Demandes de consultation</h2>

        <ul class="nav nav-tabs mb-3">
            <li class="nav-item">
                <a class="nav-link {{ $filtre === 'affectee' ? 'active' : '' }}"
                   href="{{ route('medecin.demandes.index', ['filtre' => 'affectee']) }}">À confirmer</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $filtre === 'confirmee' ? 'active' : '' }}"
                   href="{{ route('medecin.demandes.index', ['filtre' => 'confirmee']) }}">Confirmées</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $filtre === 'toutes' ? 'active' : '' }}"
                   href="{{ route('medecin.demandes.index', ['filtre' => 'toutes']) }}">Toutes</a>
            </li>
        </ul>

        <div class="card">
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Patient</th>
                            <th>Service souhaité</th>
                            <th>Urgence</th>
                            <th>Disponibilité indiquée</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($demandes as $demande)
                            <tr>
                                <td>{{ $demande->patient->prenom }} {{ $demande->patient->nom }}</td>
                                <td>{{ $demande->service_souhaite }}</td>
                                <td>
                                    <span class="badge bg-{{ $demande->urgence === 'haute' ? 'danger' : ($demande->urgence === 'moyenne' ? 'warning' : 'secondary') }}">
                                        {{ $demande->urgence }}
                                    </span>
                                </td>
                                <td>{{ Str::limit($demande->disponibilite_patient, 30) }}</td>
                                <td><span class="badge bg-info">{{ $demande->statut }}</span></td>
                                <td>
                                    <a href="{{ route('medecin.demandes.show', $demande) }}" class="btn btn-sm btn-primary">
                                        {{ $demande->statut === 'affectee' ? 'Confirmer' : 'Voir' }}
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center text-muted">Aucune demande.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $demandes->links() }}
            </div>
        </div>
    </div>
</div>
@endsection