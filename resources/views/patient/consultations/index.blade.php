@extends('layouts.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <h4 class="mb-4">Mes Consultations (moi et mes proches)</h4>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>Patient</th>
                        <th>Médecin</th>
                        <th>Diagnostic</th>
                        <th>Statut</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($consultations as $consult)
                        <tr>
                            <td>{{ $consult->date_consultation->format('d/m/Y') }}</td>
                            <td>{{ $consult->patient->prenom }} {{ $consult->patient->nom }}</td>
                            <td>Dr. {{ $consult->medecin->user->name }}</td>
                            <td>{{ Str::limit($consult->diagnostic ?? $consult->observations ?? 'En cours', 60) }}</td>
                            <td>
                            <span class="badge bg-{{ $consult->statut == 'terminee' ? 'success' : 'warning' }}">
                                {{ ucfirst($consult->statut) }}
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
