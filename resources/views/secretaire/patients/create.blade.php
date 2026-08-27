@extends('layouts.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <h4 class="mb-4">Nouveau Patient</h4>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('secretaire.patients.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Nom <span class="text-danger">*</span></label>
                                <input type="text" name="nom" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Prénom <span class="text-danger">*</span></label>
                                <input type="text" name="prenom" class="form-control" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label>Sexe <span class="text-danger">*</span></label>
                                <select name="sexe" class="form-control" required>
                                    <option value="M">Masculin</option>
                                    <option value="F">Féminin</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Date de Naissance <span class="text-danger">*</span></label>
                                <input type="date" name="date_naissance" class="form-control" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Téléphone <span class="text-danger">*</span></label>
                                <input type="text" name="telephone" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>Adresse électronique <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label>Adresse</label>
                            <textarea name="adresse" class="form-control" rows="2"></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Groupe Sanguin</label>
                                <select name="groupe_sanguin" class="form-control">
                                    <option value="Inconnu">Inconnu</option>
                                    <option value="O+">O+</option>
                                    <option value="O-">O-</option>
                                    <option value="A+">A+</option>
                                    <option value="A-">A-</option>
                                    <option value="B+">B+</option>
                                    <option value="B-">B-</option>
                                    <option value="AB+">AB+</option>
                                    <option value="AB-">AB-</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Contact d'urgence (Nom)<span class="text-danger">*</span></label>
                                <input type="text" name="contact_urgence_nom" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Contact d'urgence (Téléphone) <span class="text-danger">*</span></label>
                                <input type="text" name="contact_urgence_telephone" class="form-control" required>
                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-primary">Enregistrer le Patient</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
