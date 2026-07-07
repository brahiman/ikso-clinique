@extends('layouts.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <h4 class="mb-4">Mes Rendez-vous (pour moi et mes proches)</h4>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <th style="width: 28%; background-color: #f8f9fa;">Date & Heure</th>
                        <th>Patient</th>
                        <th>Médecin</th>
                        <th>Statut</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($rendezVous as $rdv)
                        <tr>
                            <td style="font-size: 1.1rem; font-weight: bold; color: #0d6efd;">
                                {{ $rdv->date_heure->format('d/m/Y') }}<br>
                                <small style="color: #6c757d;">{{ $rdv->date_heure->format('H:i') }}</small>
                            </td>
                            <td>
                                <strong>{{ $rdv->patient->prenom }} {{ $rdv->patient->nom }}</strong>
                            </td>
                            <td>Dr. {{ $rdv->medecin->user->name }}</td>
                            <td>
                            <span class="badge bg-{{ $rdv->statut == 'planifie' ? 'warning' : 'success' }} fs-6">
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
