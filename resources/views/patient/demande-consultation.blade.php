@extends('layouts.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Nouvelle Demande de Consultation</h4>

                            <form action="{{ route('patient.demandes.store') }}" method="POST">
                                @csrf

                                <div class="mb-3">
                                    <label>Motif de la consultation</label>
                                    <input type="text" name="motif" class="form-control" required placeholder="Ex: Douleurs au ventre">
                                </div>

                                <div class="mb-3">
                                    <label>Symptômes</label>
                                    <textarea name="symptomes" class="form-control" rows="4" placeholder="Décrivez vos symptômes..."></textarea>
                                </div>

                                <div class="mb-3">
                                    <label>Niveau d'urgence</label>
                                    <select name="urgence" class="form-control">
                                        <option value="basse">Normale</option>
                                        <option value="moyenne">Moyenne</option>
                                        <option value="haute">Urgente</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label>Disponibilités préférées</label>
                                    <textarea name="disponibilite_patient" class="form-control" rows="2" placeholder="Ex: Lundi après-midi ou Mardi matin"></textarea>
                                </div>

                                <button type="submit" class="btn btn-primary">Envoyer ma demande</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
