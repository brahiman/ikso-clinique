@extends('layouts.master')
@section('content')
<div class="page-content">
    <div class="container-fluid">
        <h2 class="mb-4">Mes patients</h2>
<table class="table">
    <thead>
        <tr>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($patients as $patient)
            <tr>
                <td>{{ $patient->nom }}</td>
                <td>{{ $patient->prenom }}</td>
                <td>{{ $patient->email }}</td>
                <td>
                    <a href="{{ route('medecin.patients.show', $patient) }}" class="btn btn-info">Voir</a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
    </div>
</div>
@endsection