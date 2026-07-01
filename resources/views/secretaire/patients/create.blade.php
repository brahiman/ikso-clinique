@extends('layouts.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <h4 class="mb-4">Nouveau Patient</h4>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('secretaire.patients.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Nom</label>
                                <input type="text" name="nom" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Prénom</label>
                                <input type="text" name="prenom" class="form-control" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label>Sexe</label>
                                <select name="sexe" class="form-control" required>
                                    <option value="M">Masculin</option>
                                    <option value="F">Féminin</option>
                                    <option value="Autre">Autre</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Date de Naissance</label>
                                <input type="date" name="date_naissance" class="form-control" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Téléphone</label>
                                <input type="text" name="telephone" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Adresse</label>
                            <textarea name="adresse" class="form-control" rows="2"></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Groupe Sanguin</label>
                                <select name="groupe_sanguin" class="form-control">
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
                                <label>Contact Urgence - Nom</label>
                                <input type="text" name="contact_urgence_nom" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Contact Urgence - Téléphone</label>
                                <input type="text" name="contact_urgence_telephone" class="form-control">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Enregistrer le Patient</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
