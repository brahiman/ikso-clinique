@extends('layouts.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <h4 class="mb-3">Consultation Directe (Patient présent à la clinique)</h4>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('secretaire.consultations.direct.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label>Rechercher un patient existant</label>
                            <input type="text" id="searchPatient" class="form-control" placeholder="Nom, prénom ou téléphone...">
                        </div>

                        <div class="mb-3">
                            <label>Patient <span class="text-danger">*</span></label>
                            <select name="patient_id" id="patientSelect" class="form-control" required>
                                <option value="">-- Sélectionner un patient --</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}">
                                        {{ $patient->nom }} {{ $patient->prenom }} — {{ $patient->telephone }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="text-end mb-3">
                            <a href="{{ route('secretaire.patients.create') }}" class="btn btn-sm btn-outline-primary">
                                + Nouveau patient
                            </a>
                        </div>

                        <div class="mb-3">
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

                        <div class="mb-3">
                            <label>Médecin <span class="text-danger">*</span></label>
                            <select name="medecin_id" class="form-control" required>
                                <option value="">-- Choisir un médecin --</option>
                                @foreach($medecins as $med)
                                    <option value="{{ $med->id }}">Dr. {{ $med->user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Motif / Raison de la visite <span class="text-danger">*</span></label>
                            <textarea name="motif" class="form-control" rows="3" required></textarea>
                        </div>

                        <div class="form-check mb-3">
                            <input type="checkbox" name="est_urgence" class="form-check-input" value="1">
                            <label class="form-check-label">Ceci est une urgence</label>
                        </div>

                        <button type="submit" class="btn btn-success">Créer la Consultation</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Recherche de patient
            $('#searchPatient').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $('#patientSelect option').each(function() {
                    if ($(this).val() === '') return;
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });

            // Validation simple avant envoi
            $('form').on('submit', function(e) {
                var patient = $('#patientSelect').val();
                var service = $('select[name="service_souhaite"]').val();
                var medecin = $('select[name="medecin_id"]').val();
                var motif = $('textarea[name="motif"]').val().trim();

                if (!patient || !service || !medecin || !motif) {
                    e.preventDefault();
                    alert('Veuillez remplir tous les champs obligatoires.');
                }
            });
        });
    </script>
@endsection
