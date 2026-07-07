@extends('layouts.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <h4 class="mb-3">Mes Rendez-vous (pour moi et mes proches)</h4>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Date & Heure</th>
                        <th>Patient</th>
                        <th>Médecin</th>
                        <th>Statut</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($rendezVous as $rdv)
                        <tr>
                            <td>{{ $rdv->date_heure->format('d/m/Y H:i') }}</td>
                            <td>{{ $rdv->patient->prenom }} {{ $rdv->patient->nom }}</td>
                            <td>Dr. {{ $rdv->medecin->user->name }}</td>
                            <td>
                            <span class="badge bg-{{ $rdv->statut == 'planifie' ? 'warning' : 'success' }}">
                                {{ ucfirst($rdv->statut) }}
                            </span>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
