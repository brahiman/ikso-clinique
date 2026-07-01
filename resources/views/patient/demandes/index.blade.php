@extends('layouts.master')

@section('title', 'Mes Demandes de Consultation')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4>Mes Demandes de Consultation</h4>
                                <a href="{{ route('patient.demandes.create') }}" class="btn btn-primary">
                                    Nouvelle Demande
                                </a>
                            </div>

                            @if($demandes->isEmpty())
                                <div class="alert alert-info">
                                    Vous n'avez aucune demande pour le moment.
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Motif</th>
                                            <th>Urgence</th>
                                            <th>Statut</th>
                                            <th>Médecin assigné</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($demandes as $demande)
                                            <tr>
                                                <td>{{ $demande->created_at->format('d/m/Y H:i') }}</td>
                                                <td>{{ $demande->motif }}</td>
                                                <td>
                                                    @if($demande->urgence == 'haute')
                                                        <span class="badge bg-danger">Urgente</span>
                                                    @elseif($demande->urgence == 'moyenne')
                                                        <span class="badge bg-warning">Moyenne</span>
                                                    @else
                                                        <span class="badge bg-info">Normale</span>
                                                    @endif
                                                </td>
                                                <td>
                                                <span class="badge bg-{{ $demande->statut == 'en_attente' ? 'warning' : 'success' }}">
                                                    {{ ucfirst($demande->statut) }}
                                                </span>
                                                </td>
                                                <td>
                                                    @if($demande->medecin)
                                                        Dr. {{ $demande->medecin->user->name }}
                                                    @else
                                                        <span class="text-muted">En attente</span>
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
        </div>
    </div>
@endsection
