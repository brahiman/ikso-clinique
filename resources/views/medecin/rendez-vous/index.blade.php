@extends('layouts.master')

@section('content')
<div class="page-content">
    <div class="container-fluid">
        <h2 class="mb-4">Mes rendez-vous</h2>

        <ul class="nav nav-tabs mb-3">
            <li class="nav-item">
                <a class="nav-link {{ $filtre === 'aujourdhui' ? 'active' : '' }}"
                   href="{{ route('medecin.rendez-vous.index', ['filtre' => 'aujourdhui']) }}">Aujourd'hui</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $filtre === 'a_venir' ? 'active' : '' }}"
                   href="{{ route('medecin.rendez-vous.index', ['filtre' => 'a_venir']) }}">À venir</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $filtre === 'tous' ? 'active' : '' }}"
                   href="{{ route('medecin.rendez-vous.index', ['filtre' => 'tous']) }}">Tous</a>
            </li>
        </ul>

        <div class="card">
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date / heure</th>
                            <th>Patient</th>
                            <th>Motif</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rendezVous as $rdv)
                            <tr>
                                <td>{{ $rdv->date_heure->format('d/m/Y H:i') }}</td>
                                <td>{{ $rdv->patient->prenom }} {{ $rdv->patient->nom }}</td>
                                <td>{{ Str::limit($rdv->motif, 40) }}</td>
                                <td><span class="badge bg-{{ $rdv->status_color }}">{{ $rdv->statut }}</span></td>
                                <td>
                                    @if(in_array($rdv->statut, ['planifie', 'confirme']))
                                        <form action="{{ route('medecin.rendez-vous.demarrer', $rdv) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button class="btn btn-sm btn-primary">Démarrer la consultation</button>
                                        </form>
                                    @elseif($rdv->statut === 'en_cours')
                                        <a href="{{ route('medecin.rendez-vous.demarrer', $rdv) }}" class="btn btn-sm btn-outline-primary">Reprendre</a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted">Aucun rendez-vous.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $rendezVous->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
