@extends('layouts.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <h4 class="mb-4">Liste des Consultations</h4>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Date</th>
                                <th>Patient</th>
                                <th>Médecin</th>
                                <th>Motif</th>
                                <th>Statut</th>
                                <th>Type</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($consultations as $consult)
                                <tr>
                                    <td>{{ $consult->date_consultation->format('d/m/Y H:i') }}</td>
                                    <td>{{ $consult->patient->prenom }} {{ $consult->patient->nom }}</td>
                                    <td>Dr. {{ $consult->medecin->user->name }}</td>
                                    <td>{{ $consult->motif_direct ?? $consult->observations }}</td>
                                    <td>
                                    <span class="badge bg-{{ $consult->statut == 'terminee' ? 'success' : 'warning' }}">
                                        {{ ucfirst($consult->statut) }}
                                    </span>
                                    </td>
                                    <td>
                                        @if($consult->isDirecte())
                                            <span class="badge bg-info">Directe</span>
                                        @else
                                            <span class="badge bg-secondary">RDV</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-info">Voir</a>
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
