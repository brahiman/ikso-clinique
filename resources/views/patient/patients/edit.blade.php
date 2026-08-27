@extends('layouts.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0">Modifier le patient : {{ $patient->prenom }} {{ $patient->nom }}</h4>
                <a href="{{ route('patient.patients.index') }}" class="btn btn-secondary">
                    Retour à la liste
                </a>
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('patient.patients.update', $patient) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Nom <span class="text-danger">*</span></label>
                                <input type="text" name="nom" class="form-control @error('nom') is-invalid @enderror"
                                       value="{{ old('nom', $patient->nom) }}" required>
                                @error('nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Prénom <span class="text-danger">*</span></label>
                                <input type="text" name="prenom" class="form-control @error('prenom') is-invalid @enderror"
                                       value="{{ old('prenom', $patient->prenom) }}" required>
                                @error('prenom')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label>Sexe</label>
                                <select name="sexe" class="form-control">
                                    <option value="">—</option>
                                    <option value="M" {{ old('sexe', $patient->sexe) == 'M' ? 'selected' : '' }}>Masculin</option>
                                    <option value="F" {{ old('sexe', $patient->sexe) == 'F' ? 'selected' : '' }}>Féminin</option>
                                    <option value="Autre" {{ old('sexe', $patient->sexe) == 'Autre' ? 'selected' : '' }}>Autre</option>
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label>Date de naissance</label>
                                <input type="date" name="date_naissance" class="form-control"
                                       value="{{ old('date_naissance', $patient->date_naissance?->format('Y-m-d')) }}">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label>Téléphone</label>
                                <input type="text" name="telephone" class="form-control"
                                       value="{{ old('telephone', $patient->telephone) }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control"
                                   value="{{ old('email', $patient->email) }}">
                        </div>

                        <div class="mb-3">
                            <label>Adresse</label>
                            <textarea name="adresse" class="form-control" rows="2">{{ old('adresse', $patient->adresse) }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label>Groupe sanguin</label>
                                <select name="groupe_sanguin" class="form-control">
                                    <option value="Inconnu" {{ old('groupe_sanguin', $patient->groupe_sanguin) == 'Inconnu' ? 'selected' : '' }}>Inconnu</option>
                                    <option value="O+" {{ old('groupe_sanguin', $patient->groupe_sanguin) == 'O+' ? 'selected' : '' }}>O+</option>
                                    <option value="O-" {{ old('groupe_sanguin', $patient->groupe_sanguin) == 'O-' ? 'selected' : '' }}>O-</option>
                                    <option value="A+" {{ old('groupe_sanguin', $patient->groupe_sanguin) == 'A+' ? 'selected' : '' }}>A+</option>
                                    <option value="A-" {{ old('groupe_sanguin', $patient->groupe_sanguin) == 'A-' ? 'selected' : '' }}>A-</option>
                                    <option value="B+" {{ old('groupe_sanguin', $patient->groupe_sanguin) == 'B+' ? 'selected' : '' }}>B+</option>
                                    <option value="B-" {{ old('groupe_sanguin', $patient->groupe_sanguin) == 'B-' ? 'selected' : '' }}>B-</option>
                                    <option value="AB+" {{ old('groupe_sanguin', $patient->groupe_sanguin) == 'AB+' ? 'selected' : '' }}>AB+</option>
                                    <option value="AB-" {{ old('groupe_sanguin', $patient->groupe_sanguin) == 'AB-' ? 'selected' : '' }}>AB-</option>
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label>Contact d’urgence – Nom</label>
                                <input type="text" name="contact_urgence_nom" class="form-control"
                                       value="{{ old('contact_urgence_nom', $patient->contact_urgence_nom) }}">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label>Contact d’urgence – Téléphone</label>
                                <input type="text" name="contact_urgence_telephone" class="form-control"
                                       value="{{ old('contact_urgence_telephone', $patient->contact_urgence_telephone) }}">
                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <a href="{{ route('patient.patients.index') }}" class="btn btn-secondary me-2">Annuler</a>
                            <button type="submit" class="btn btn-primary">
                                Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
