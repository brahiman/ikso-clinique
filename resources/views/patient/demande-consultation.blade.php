@extends('layouts.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="text-center mb-4">Demande de Rendez-vous</h3>

                            @if(isset($patient))
                                <div class="alert alert-info mb-4">
                                    <strong>Patient sélectionné :</strong>
                                    {{ $patient->prenom }} {{ $patient->nom }}
                                    @if($patient->telephone)
                                        — {{ $patient->telephone }}
                                    @endif
                                </div>
                            @endif

                            <form action="{{ isset($patient) ? route('patient.demandes.store-for-patient', $patient) : route('patient.demandes.store') }}" method="POST">
                                @csrf
                                @if(!isset($patient))
                                <!-- Informations Personnelles -->
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label>Nom <span class="text-danger">*</span></label>
                                        <input type="text" name="nom" class="form-control" required placeholder="Votre nom">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Prénom <span class="text-danger">*</span></label>
                                        <input type="text" name="prenom" class="form-control" required placeholder="Votre prénom">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label>Date de Naissance <span class="text-danger">*</span></label>
                                        <input type="date" name="date_naissance" class="form-control" required>
                                    </div>
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

                                <div class="mb-3">
                                    <label>Numéro de téléphone <span class="text-danger">*</span></label>
                                    <input type="tel" name="telephone" class="form-control" required placeholder="Votre numéro">
                                </div>

                                <div class="mb-3">
                                    <label>Adresse électronique <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control" required placeholder="Votre email">
                                </div>

                                <div class="mb-3">
                                    <label>Localisation <span class="text-danger">*</span></label>
                                    <input type="text" name="localisation" class="form-control" required placeholder="Votre quartier ou ville">
                                </div>

                                <div class="mb-3">
                                    <label>Adresse complète / Point de repère</label>
                                    <input type="text" name="adresse" class="form-control" placeholder="Ex : près du marché central">
                                </div>

                                <!-- Contact Urgence -->
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
                                @endif
                                <!-- Service et Urgence -->
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label>Service souhaité <span class="text-danger">*</span></label>
                                        <select name="service_souhaite" class="form-control" required>
                                            <option value="">Sélectionnez un service</option>
                                            <option value="Consultation médicale">Consultation médicale</option>
                                            <option value="Référencement vers spécialiste">Référencement vers spécialiste</option>
                                            <option value="Suivi hormonothérapie">Suivi hormonothérapie</option>
                                            <option value="Soutien psychosocial">Soutien psychosocial</option>
                                            <option value="Vaccination">Vaccination</option>
                                            <option value="Autre">Autre</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Niveau d'urgence <span class="text-danger">*</span></label>
                                        <select name="urgence" class="form-control" required>
                                            <option value="basse">Normale</option>
                                            <option value="moyenne">Moyenne</option>
                                            <option value="haute">Urgente</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label>Mode de consultation <span class="text-danger">*</span></label>
                                    <select name="mode_consultation" class="form-control" required>
                                        <option value="presentiel">À la clinique (présentiel)</option>
                                        <option value="distance">À distance (téléphone ou vidéo)</option>
                                    </select>
                                    <small class="text-muted">
                                        Choisissez « À distance » si le patient ne peut pas se déplacer à la clinique.
                                    </small>
                                </div>

                                <!-- Préférences et Disponibilités -->
                                <div class="mb-3">
                                    <label>Préférence particulière (médecin)</label>
                                    <input type="text" name="pref_medecin" class="form-control" placeholder="Ex : femme médecin, spécialiste...">
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label>Date du rendez-vous souhaitée</label>
                                        <input type="date" name="date_souhaitee" class="form-control">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Heure souhaitée</label>
                                        <select name="heure_souhaitee" class="form-control">
                                            <option value="">Choisissez une heure</option>
                                            <option value="08:00">08:00</option>
                                            <option value="09:00">09:00</option>
                                            <option value="10:00">10:00</option>
                                            <option value="11:00">11:00</option>
                                            <option value="14:00">14:00</option>
                                            <option value="15:00">15:00</option>
                                            <option value="16:00">16:00</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label>Autre disponibilité</label>
                                    <textarea name="disponibilite_patient" class="form-control" rows="2" placeholder="Ex : matin, après-midi"></textarea>
                                </div>

                                <div class="mb-3">
                                    <label>Résumé de votre besoin <span class="text-danger">*</span></label>
                                    <textarea name="symptomes" class="form-control" rows="4" required placeholder="Décrivez brièvement votre besoin..."></textarea>
                                </div>

                                <!-- Consentement -->
                                <div class="form-check mb-3">
                                    <input type="checkbox" class="form-check-input" id="consentement" name="consentement" required>
                                    <label class="form-check-label" for="consentement">
                                        Nous devrons vous contacter pour confirmer votre rendez-vous. J'accepte.
                                    </label>
                                </div>
                                <button type="submit" class="btn btn-primary btn-lg w-100">Envoyer ma demande de rendez-vous</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
