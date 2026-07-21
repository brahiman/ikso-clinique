@extends('layouts.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-4">Gestion des Patients</h4>
                <p class="text-muted">Liste complète des patients enregistrés et leur médecin référent.</p>
                <a href="{{ route('secretaire.patients.create') }}" class="btn btn-primary">
                    <i class="ri-add-line"></i> Nouveau Patient
                </a>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Nom Complet</th>
                                <th>Téléphone</th>
                                <th>Date Naissance</th>
                                <th>Groupe Sanguin</th>
                                <th>Médecin(s) Assigné(s)</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($patients as $index => $patient)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <strong>{{ $patient->prenom }} {{ $patient->nom }}</strong>
                                    </td>
                                    <td>{{ $patient->telephone }}</td>
                                    <td>{{ $patient->date_naissance?->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $patient->groupe_sanguin }}</span>
                                    </td>
                                    <td>
                                        @if($patient->medecins->count() > 0)
                                            @foreach($patient->medecins as $med)
                                                <span class="badge bg-success">Dr. {{ $med->user->name }}</span><br>
                                            @endforeach
                                        @else
                                            <span class="text-muted small">Aucun médecin assigné</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('secretaire.patients.show', $patient) }}" class="btn btn-sm btn-info">
                                            Voir
                                        </a>
                                        <a href="{{ route('secretaire.patients.edit', $patient) }}" class="btn btn-sm btn-warning">
                                            Modifier
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
