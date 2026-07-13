@extends('layouts.master')

@section('content')
<div class="page-content">
    <div class="container-fluid">
        <h2 class="mb-4">Mes consultations</h2>

        <div class="card mb-4">
            <div class="card-body">
                <h5>En cours ({{ $enCours->count() }})</h5>
                <table class="table">
                    <tbody>
                        @forelse($enCours as $c)
                            <tr>
                                <td>{{ $c->patient->prenom }} {{ $c->patient->nom }}</td>
                                <td>{{ $c->isDirecte() ? 'Accueil direct' : 'Rendez-vous' }}</td>
                                <td>{{ $c->date_consultation->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('medecin.consultations.edit', $c) }}" class="btn btn-sm btn-primary">Reprendre</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td class="text-muted text-center">Aucune consultation en cours.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5>Terminées</h5>
                <table class="table">
                    <tbody>
                        @forelse($terminees as $c)
                            <tr>
                                <td>{{ $c->patient->prenom }} {{ $c->patient->nom }}</td>
                                <td>{{ $c->resume }}</td>
                                <td>{{ $c->date_consultation->format('d/m/Y') }}</td>
                                <td><a href="{{ route('medecin.consultations.show', $c) }}" class="btn btn-sm btn-outline-secondary">Voir</a></td>
                            </tr>
                        @empty
                            <tr><td class="text-muted text-center">Aucune consultation terminée.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $terminees->links() }}
            </div>
        </div>
    </div>
</div>
@endsection