@extends('layouts.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="d-flex justify-content-between mb-4">
                <h4>Liste des Patients</h4>
                <a href="{{ route('secretaire.patients.create') }}" class="btn btn-primary">Nouveau Patient</a>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Nom Complet</th>
                        <th>Téléphone</th>
                        <th>Date de Naissance</th>
                        <th>Groupe Sanguin</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($patients as $patient)
                        <tr>
                            <td>{{ $patient->prenom }} {{ $patient->nom }}</td>
                            <td>{{ $patient->telephone }}</td>
                            <td>{{ $patient->date_naissance?->format('d/m/Y') }}</td>
                            <td>{{ $patient->groupe_sanguin }}</td>
                            <td>
                                <a href="#" class="btn btn-sm btn-info">Voir</a>

                                <a href="#" class="btn btn-sm btn-warning">Modifier</a>

                                <!-- Bouton Affectation -->
                                <a href="{{ route('secretaire.patients.affecter-form', $patient) }}"
                                   class="btn btn-sm btn-success">
                                    <i class="ri-user-add-line"></i> Affecter
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
