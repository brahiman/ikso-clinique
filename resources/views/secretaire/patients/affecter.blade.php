@extends('layouts.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <h4>Affecter le patient : {{ $patient->prenom }} {{ $patient->nom }}</h4>

            <form action="{{ route('secretaire.patients.affecter', $patient) }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label>Choisir un médecin</label>
                    <select name="medecin_id" class="form-control" required>
                        @foreach($medecins as $med)
                            <option value="{{ $med->id }}">
                                Dr. {{ $med->user->name }} - {{ $med->specialite?->nom ?? 'Généraliste' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-success">Affecter</button>
            </form>
        </div>
    </div>
@endsection
