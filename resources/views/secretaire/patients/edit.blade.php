@extends('layouts.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <h4>Modifier le Patient</h4>

            <div class="card mt-3">
                <div class="card-body">
                    <form action="{{ route('secretaire.patients.update', $patient) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Nom <span class="text-danger">*</span></label>
                                <input type="text" name="nom" class="form-control" value="{{ $patient->nom }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Prénom <span class="text-danger">*</span></label>
                                <input type="text" name="prenom" class="form-control" value="{{ $patient->prenom }}" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label>Sexe</label>
                                <select name="sexe" class="form-control">
                                    <option value="M" {{ $patient->sexe == 'M' ? 'selected' : '' }}>Masculin</option>
                                    <option value="F" {{ $patient->sexe == 'F' ? 'selected' : '' }}>Féminin</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Date de Naissance</label>
                                <input type="date" name="date_naissance" class="form-control" value="{{ $patient->date_naissance?->format('Y-m-d') }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Téléphone <span class="text-danger">*</span></label>
                                <input type="text" name="telephone" class="form-control" value="{{ $patient->telephone }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" value="{{ $patient->email }}">
                        </div>

                        <div class="mb-3">
                            <label>Adresse</label>
                            <textarea name="adresse" class="form-control" rows="2">{{ $patient->adresse }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Groupe Sanguin</label>
                                <select name="groupe_sanguin" class="form-control">
                                    <option value="Inconnu" {{ $patient->groupe_sanguin == 'Inconnu' ? 'selected' : '' }}>Inconnu</option>
                                    <option value="O+" {{ $patient->groupe_sanguin == 'O+' ? 'selected' : '' }}>O+</option>
                                    <option value="O-" {{ $patient->groupe_sanguin == 'O-' ? 'selected' : '' }}>O-</option>
                                    <option value="A+" {{ $patient->groupe_sanguin == 'A+' ? 'selected' : '' }}>A+</option>
                                    <option value="A-" {{ $patient->groupe_sanguin == 'A-' ? 'selected' : '' }}>A-</option>
                                    <option value="B+" {{ $patient->groupe_sanguin == 'B+' ? 'selected' : '' }}>B+</option>
                                    <option value="B-" {{ $patient->groupe_sanguin == 'B-' ? 'selected' : '' }}>B-</option>
                                    <option value="AB+" {{ $patient->groupe_sanguin == 'AB+' ? 'selected' : '' }}>AB+</option>
                                    <option value="AB-" {{ $patient->groupe_sanguin == 'AB-' ? 'selected' : '' }}>AB-</option>
                                    <!-- Ajoute les autres groupes sanguins -->
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Contact Urgence - Nom</label>
                                <input type="text" name="contact_urgence_nom" class="form-control" value="{{ $patient->contact_urgence_nom }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Contact Urgence - Téléphone</label>
                                <input type="text" name="contact_urgence_telephone" class="form-control" value="{{ $patient->contact_urgence_telephone }}">
                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
