@extends('layouts.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <!-- Header -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1">
                                <i class="ri-stethoscope-line me-2 text-primary"></i>
                                Consultation Directe
                            </h4>
                            <p class="text-muted mb-0">
                                <i class="ri-map-pin-line me-1"></i>
                                Patient présent à la clinique
                            </p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('secretaire.consultations.index') }}" class="btn btn-secondary">
                                <i class="ri-history-line me-1"></i> Historique
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white d-flex align-items-center">
                            <div class="avatar-sm bg-primary-subtle rounded-circle d-flex align-items-center justify-content-center me-3">
                                <i class="ri-user-heart-line text-primary"></i>
                            </div>
                            <h5 class="mb-0">Nouvelle Consultation</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('secretaire.consultations.direct.store') }}" method="POST" id="consultationForm">
                                @csrf

                                <!-- Section Patient -->
                                <div class="section-patient mb-4">
                                    <h6 class="fw-bold text-primary mb-3">
                                        <i class="ri-user-search-line me-2"></i>
                                        Identification du Patient
                                    </h6>

                                    <!-- Recherche patient -->
                                    <div class="mb-3">
                                        <label class="fw-bold">
                                            <i class="ri-search-line me-1"></i>
                                            Rechercher un patient existant
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white">
                                                <i class="ri-search-line"></i>
                                            </span>
                                            <input type="text"
                                                   id="searchPatient"
                                                   class="form-control"
                                                   placeholder="Nom, prénom ou téléphone..."
                                                   autocomplete="off">
                                            <button class="btn btn-outline-secondary" type="button" id="clearSearch">
                                                <i class="ri-close-line"></i>
                                            </button>
                                        </div>
                                        <small class="text-muted">
                                            <i class="ri-information-line me-1"></i>
                                            Commencez à taper pour filtrer la liste
                                        </small>
                                    </div>

                                    <!-- Sélection patient -->
                                    <div class="mb-3">
                                        <label class="fw-bold">
                                            Patient <span class="text-danger">*</span>
                                        </label>
                                        <select name="patient_id" id="patientSelect" class="form-select form-select-lg" required>
                                            <option value="">-- Sélectionner un patient --</option>
                                            @forelse($patients as $patient)
                                                <option value="{{ $patient->id }}"
                                                        data-nom="{{ strtolower($patient->nom) }}"
                                                        data-prenom="{{ strtolower($patient->prenom) }}"
                                                        data-telephone="{{ $patient->telephone }}">
                                                    {{ $patient->nom }} {{ $patient->prenom }} — {{ $patient->telephone ?? 'Pas de téléphone' }}
                                                </option>
                                            @empty
                                                <option value="" disabled>Aucun patient enregistré</option>
                                            @endforelse
                                        </select>
                                        @error('patient_id')
                                        <div class="text-danger mt-1">
                                            <i class="ri-error-warning-line me-1"></i>{{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <!-- Bouton nouveau patient -->
                                    <div class="text-end">
                                        <a href="{{ route('secretaire.patients.create') }}" class="btn btn-outline-primary" target="_blank">
                                            <i class="ri-user-add-line me-1"></i> Créer un nouveau patient
                                        </a>
                                    </div>
                                </div>

                                <hr class="my-4">

                                <!-- Section Détails -->
                                <div class="section-consultation">
                                    <h6 class="fw-bold text-primary mb-3">
                                        <i class="ri-file-list-3-line me-2"></i>
                                        Détails de la Consultation
                                    </h6>

                                    <!-- Service -->
                                    <div class="mb-3">
                                        <label class="fw-bold">
                                            <i class="ri-hospital-line me-1"></i>
                                            Service souhaité <span class="text-danger">*</span>
                                        </label>
                                        <select name="service_souhaite" id="serviceSouhaite" class="form-select" required>
                                            <option value="">Sélectionnez un service</option>
                                            <option value="Consultation médicale">🏥 Consultation médicale</option>
                                            <option value="Référencement vers spécialiste">👨‍⚕️ Référencement vers spécialiste</option>
                                            <option value="Suivi hormonothérapie">💊 Suivi hormonothérapie</option>
                                            <option value="Soutien psychosocial">🧠 Soutien psychosocial</option>
                                            <option value="Vaccination">💉 Vaccination</option>
                                            <option value="Autre">📋 Autre</option>
                                        </select>
                                        @error('service_souhaite')
                                        <div class="text-danger mt-1">
                                            <i class="ri-error-warning-line me-1"></i>{{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <!-- Service Autre -->
                                    <div class="mb-3" id="autreServiceDiv" style="display: none;">
                                        <label class="fw-bold">
                                            <i class="ri-edit-line me-1"></i>
                                            Précisez le service
                                        </label>
                                        <input type="text"
                                               name="autre_service"
                                               id="autreService"
                                               class="form-control"
                                               placeholder="Précisez le service...">
                                    </div>

                                    <!-- Médecin -->
                                    <div class="mb-3">
                                        <label class="fw-bold">
                                            <i class="ri-user-star-line me-1"></i>
                                            Médecin <span class="text-danger">*</span>
                                        </label>
                                        <select name="medecin_id" id="medecinSelect" class="form-select" required>
                                            <option value="">-- Choisir un médecin --</option>
                                            @forelse($medecins as $med)
                                                <option value="{{ $med->id }}">
                                                    Dr. {{ $med->user->name ?? 'Non assigné' }}
                                                    @if(isset($med->specialite) && $med->specialite)
                                                        - {{ $med->specialite }}
                                                    @endif
                                                </option>
                                            @empty
                                                <option value="" disabled>Aucun médecin disponible</option>
                                            @endforelse
                                        </select>
                                        @error('medecin_id')
                                        <div class="text-danger mt-1">
                                            <i class="ri-error-warning-line me-1"></i>{{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <!-- Motif -->
                                    <div class="mb-3">
                                        <label class="fw-bold">
                                            <i class="ri-chat-3-line me-1"></i>
                                            Motif / Raison de la visite <span class="text-danger">*</span>
                                        </label>
                                        <textarea name="motif"
                                                  id="motif"
                                                  class="form-control"
                                                  rows="4"
                                                  placeholder="Décrivez le motif de la consultation..."
                                                  required></textarea>
                                        <div class="d-flex justify-content-between mt-1">
                                            <small class="text-muted">
                                                <span id="charCount">0</span>/500 caractères
                                            </small>
                                            <small class="text-muted">
                                                <i class="ri-information-line me-1"></i>
                                                Soyez précis pour une meilleure prise en charge
                                            </small>
                                        </div>
                                        @error('motif')
                                        <div class="text-danger mt-1">
                                            <i class="ri-error-warning-line me-1"></i>{{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <!-- Urgence -->
                                    <div class="mb-3 p-3 border rounded">
                                        <div class="form-check form-switch">
                                            <input type="checkbox"
                                                   name="est_urgence"
                                                   class="form-check-input"
                                                   value="1"
                                                   id="urgenceSwitch">
                                            <label class="form-check-label fw-bold" for="urgenceSwitch">
                                                <i class="ri-alert-fill text-danger me-1"></i>
                                                <span class="text-danger">Ceci est une urgence</span>
                                            </label>
                                        </div>
                                        <div id="urgenceInfo" style="display: none;" class="mt-2">
                                            <div class="alert alert-danger mb-0">
                                                <i class="ri-alert-fill me-2"></i>
                                                <strong>Attention :</strong> Cette consultation sera marquée comme prioritaire.
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr class="my-4">

                                <!-- Résumé -->
                                <div class="section-resume mb-4" id="resumeSection" style="display: none;">
                                    <h6 class="fw-bold text-primary mb-3">
                                        <i class="ri-file-list-3-line me-2"></i>
                                        Résumé de la consultation
                                    </h6>
                                    <div class="p-3 bg-light rounded">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p class="mb-1"><strong>Patient :</strong> <span id="resumePatient">-</span></p>
                                                <p class="mb-1"><strong>Service :</strong> <span id="resumeService">-</span></p>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="mb-1"><strong>Médecin :</strong> <span id="resumeMedecin">-</span></p>
                                                <p class="mb-1"><strong>Urgence :</strong> <span id="resumeUrgence">Non</span></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Boutons -->
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <button type="reset" class="btn btn-outline-danger">
                                            <i class="ri-refresh-line me-1"></i> Réinitialiser
                                        </button>
                                    </div>
                                    <button type="submit" class="btn btn-success btn-lg" id="submitBtn">
                                        <i class="ri-check-line me-1"></i> Créer la Consultation
                                        <span class="spinner-border spinner-border-sm d-none ms-1" id="loadingSpinner"></span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // ============================================
            // RECHERCHE DE PATIENT
            // ============================================

            $('#searchPatient').on('keyup', function() {
                var searchValue = $(this).val().toLowerCase().trim();

                if (searchValue === '') {
                    $('#patientSelect option').show();
                    $('#patientSelect').val('');
                } else {
                    $('#patientSelect option').each(function() {
                        if ($(this).val() === '') {
                            $(this).show();
                            return;
                        }

                        var optionText = $(this).text().toLowerCase();
                        var nom = $(this).data('nom') || '';
                        var prenom = $(this).data('prenom') || '';
                        var telephone = $(this).data('telephone') || '';

                        if (optionText.indexOf(searchValue) > -1 ||
                            nom.indexOf(searchValue) > -1 ||
                            prenom.indexOf(searchValue) > -1 ||
                            telephone.indexOf(searchValue) > -1) {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    });

                    if ($('#patientSelect option:selected').is(':hidden')) {
                        $('#patientSelect').val('');
                    }
                }

                updateResume();
            });

            $('#clearSearch').on('click', function() {
                $('#searchPatient').val('');
                $('#patientSelect option').show();
                $('#patientSelect').val('');
                updateResume();
            });

            // ============================================
            // CHANGEMENT DE PATIENT
            // ============================================

            $('#patientSelect').on('change', function() {
                updateResume();
                if ($(this).val()) {
                    $('#serviceSouhaite').focus();
                }
            });

            // ============================================
            // SERVICE "AUTRE"
            // ============================================

            $('#serviceSouhaite').on('change', function() {
                if ($(this).val() === 'Autre') {
                    $('#autreServiceDiv').slideDown();
                    $('#autreService').attr('required', true);
                } else {
                    $('#autreServiceDiv').slideUp();
                    $('#autreService').attr('required', false);
                    $('#autreService').val('');
                }
                updateResume();
            });

            // ============================================
            // GESTION DE L'URGENCE
            // ============================================

            $('#urgenceSwitch').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#urgenceInfo').slideDown();
                    if (!confirm('⚠️ Confirmez-vous qu\'il s\'agit d\'une urgence médicale ?\n\nCette action marquera la consultation comme prioritaire.')) {
                        $(this).prop('checked', false);
                        $('#urgenceInfo').slideUp();
                    }
                } else {
                    $('#urgenceInfo').slideUp();
                }
                updateResume();
            });

            // ============================================
            // COMPTEUR DE CARACTÈRES
            // ============================================

            $('#motif').on('input', function() {
                var charCount = $(this).val().length;
                var maxChars = 500;

                $('#charCount').text(charCount);

                if (charCount > maxChars * 0.8) {
                    $('#charCount').addClass('text-danger');
                } else if (charCount > maxChars * 0.5) {
                    $('#charCount').removeClass('text-danger').addClass('text-warning');
                } else {
                    $('#charCount').removeClass('text-danger text-warning');
                }

                if (charCount > maxChars) {
                    $(this).val($(this).val().substring(0, maxChars));
                    $('#charCount').text(maxChars);
                }
            });

            // ============================================
            // MISE À JOUR DU RÉSUMÉ
            // ============================================

            function updateResume() {
                var patient = $('#patientSelect option:selected').text() || '-';
                var service = $('#serviceSouhaite option:selected').text() || '-';
                var medecin = $('#medecinSelect option:selected').text() || '-';
                var urgence = $('#urgenceSwitch').is(':checked') ?
                    '<span class="text-danger fw-bold">OUI - URGENT</span>' :
                    'Non';

                if ($('#patientSelect').val()) {
                    $('#resumeSection').slideDown();
                    $('#resumePatient').text(patient !== '-- Sélectionner un patient --' ? patient : '-');
                    $('#resumeService').text(service !== 'Sélectionnez un service' ? service : '-');
                    $('#resumeMedecin').text(medecin !== '-- Choisir un médecin --' ? medecin : '-');
                    $('#resumeUrgence').html(urgence);
                } else {
                    $('#resumeSection').slideUp();
                }
            }

            // ============================================
            // VALIDATION DU FORMULAIRE
            // ============================================

            $('#consultationForm').on('submit', function(e) {
                var isValid = true;
                var errorMessage = '';

                if (!$('#patientSelect').val()) {
                    errorMessage += '• Veuillez sélectionner un patient.\n';
                    $('#patientSelect').addClass('is-invalid');
                    isValid = false;
                } else {
                    $('#patientSelect').removeClass('is-invalid');
                }

                if (!$('#serviceSouhaite').val()) {
                    errorMessage += '• Veuillez sélectionner un service.\n';
                    $('#serviceSouhaite').addClass('is-invalid');
                    isValid = false;
                } else {
                    $('#serviceSouhaite').removeClass('is-invalid');

                    if ($('#serviceSouhaite').val() === 'Autre' && !$('#autreService').val().trim()) {
                        errorMessage += '• Veuillez préciser le service.\n';
                        $('#autreService').addClass('is-invalid');
                        isValid = false;
                    } else {
                        $('#autreService').removeClass('is-invalid');
                    }
                }

                if (!$('#medecinSelect').val()) {
                    errorMessage += '• Veuillez sélectionner un médecin.\n';
                    $('#medecinSelect').addClass('is-invalid');
                    isValid = false;
                } else {
                    $('#medecinSelect').removeClass('is-invalid');
                }

                if (!$('#motif').val().trim()) {
                    errorMessage += '• Veuillez décrire le motif de la visite.\n';
                    $('#motif').addClass('is-invalid');
                    isValid = false;
                } else if ($('#motif').val().trim().length < 3) {
                    errorMessage += '• Le motif doit contenir au moins 3 caractères.\n';
                    $('#motif').addClass('is-invalid');
                    isValid = false;
                } else {
                    $('#motif').removeClass('is-invalid');
                }

                if (!isValid) {
                    e.preventDefault();
                    alert('⚠️ Veuillez corriger les erreurs suivantes :\n\n' + errorMessage);

                    var firstError = $('.is-invalid:first');
                    if (firstError.length) {
                        $('html, body').animate({
                            scrollTop: firstError.offset().top - 100
                        }, 500);
                        firstError.focus();
                    }
                } else {
                    $('#submitBtn').prop('disabled', true);
                    $('#loadingSpinner').removeClass('d-none');
                }
            });

            // ============================================
            // RÉINITIALISATION
            // ============================================

            $('button[type="reset"]').on('click', function() {
                $('#autreServiceDiv').slideUp();
                $('#urgenceInfo').slideUp();
                $('#resumeSection').slideUp();
                $('#charCount').text('0');
                $('.is-invalid').removeClass('is-invalid');
                $('#patientSelect option').show();
            });

            // ============================================
            // RACCOURCIS CLAVIER
            // ============================================

            $(document).on('keydown', function(e) {
                if (e.ctrlKey && e.key === 'Enter') {
                    e.preventDefault();
                    $('#consultationForm').submit();
                }
            });

            $('#searchPatient').on('keydown', function(e) {
                if (e.key === 'Escape') {
                    $('#clearSearch').click();
                }
            });

            // ============================================
            // INITIALISATION
            // ============================================

            $('#searchPatient').focus();
            $('#charCount').text($('#motif').val().length);
            updateResume();
        });
    </script>

    <style>
        .avatar-sm {
            width: 40px;
            height: 40px;
            min-width: 40px;
        }

        .form-select.is-invalid,
        .form-control.is-invalid {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }

        .section-patient,
        .section-consultation {
            transition: all 0.3s ease;
        }

        #resumeSection {
            transition: all 0.3s ease;
        }

        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        .is-invalid {
            animation: shake 0.3s ease-in-out;
        }

        .spinner-border-sm {
            margin-left: 5px;
        }

        @media (max-width: 768px) {
            .btn-lg {
                padding: 0.5rem 1rem;
                font-size: 0.875rem;
            }
        }
    </style>
@endsection
