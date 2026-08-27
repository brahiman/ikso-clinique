@extends('layouts.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <!-- Header -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1">
                                <i class="ri-team-line me-2"></i>Mes Patients
                            </h4>
                            <p class="text-muted mb-0">
                                {{ $patients->count() }} patient(s) enregistré(s)
                            </p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="" class="btn btn-primary">
                                <i class="ri-user-add-line me-1"></i> Nouveau Patient
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recherche -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="ri-search-line"></i>
                        </span>
                        <input type="text"
                               id="searchPatient"
                               class="form-control"
                               placeholder="Rechercher par nom, prénom ou téléphone...">
                        <button class="btn btn-outline-secondary" type="button" id="clearSearch">
                            <i class="ri-close-line"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-3">
                    <select id="filterGroupeSanguin" class="form-select">
                        <option value="">Tous les groupes sanguins</option>
                        <option value="A+">A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                    </select>
                </div>
            </div>

            <!-- Liste des patients -->
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    @if($patients->isEmpty())
                        <div class="text-center py-5">
                            <i class="ri-user-search-line display-1 text-muted"></i>
                            <h5 class="mt-3">Aucun patient enregistré</h5>
                            <p class="text-muted">Commencez par ajouter votre premier patient</p>
                            <a href="" class="btn btn-primary mt-2">
                                <i class="ri-user-add-line me-1"></i> Ajouter un patient
                            </a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle" id="patientsTable">
                                <thead class="table-light">
                                <tr>
                                    <th>Patient</th>
                                    <th>Contact</th>
                                    <th>Date de naissance</th>
                                    <th>Groupe sanguin</th>
                                    <th>Statut</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($patients as $patient)
                                    <tr class="patient-row"
                                        data-search="{{ strtolower($patient->prenom . ' ' . $patient->nom . ' ' . $patient->telephone) }}"
                                        data-groupe="{{ $patient->groupe_sanguin ?? '' }}">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-primary-subtle rounded-circle d-flex align-items-center justify-content-center me-3">
                                                    <span class="text-primary fw-bold">
                                                        {{ strtoupper(substr($patient->prenom, 0, 1)) }}{{ strtoupper(substr($patient->nom, 0, 1)) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $patient->prenom }} {{ $patient->nom }}</h6>
                                                    <small class="text-muted">ID: #{{ $patient->id }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <i class="ri-phone-line me-1 text-muted"></i>
                                                {{ $patient->telephone ?? '—' }}
                                            </div>
                                            <small class="text-muted">
                                                <i class="ri-mail-line me-1"></i>
                                                {{ $patient->email ?? 'Pas d\'email' }}
                                            </small>
                                        </td>
                                        <td>
                                            @if($patient->date_naissance)
                                                <div>
                                                    {{ $patient->date_naissance->format('d/m/Y') }}
                                                </div>
                                                <small class="text-muted">
                                                    {{ $patient->date_naissance->age }} ans
                                                </small>
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td>
                                            @if($patient->groupe_sanguin)
                                                <span class="badge bg-danger-subtle text-danger">
                                                    <i class="ri-drop-line me-1"></i>
                                                    {{ $patient->groupe_sanguin }}
                                                </span>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($patient->est_actif ?? true)
                                                <span class="badge bg-success-subtle text-success">
                                                    <i class="ri-checkbox-circle-line me-1"></i> Actif
                                                </span>
                                            @else
                                                <span class="badge bg-secondary-subtle text-secondary">
                                                    Inactif
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <div class="btn-group">
                                                <a href="{{ route('patient.patients.show', $patient) }}"
                                                   class="btn btn-sm btn-outline-primary"
                                                   title="Voir les détails">
                                                    <i class="ri-eye-line"></i>
                                                </a>
{{--                                                <a href="{{ route('patient.patients.edit', $patient) }}"--}}
{{--                                                   class="btn btn-sm btn-outline-warning"--}}
{{--                                                   title="Modifier">--}}
{{--                                                    <i class="ri-edit-line"></i>--}}
{{--                                                </a>--}}
{{--                                                <button type="button"--}}
{{--                                                        class="btn btn-sm btn-outline-danger"--}}
{{--                                                        onclick="confirmDelete({{ $patient->id }})"--}}
{{--                                                        title="Supprimer">--}}
{{--                                                    <i class="ri-delete-bin-line"></i>--}}
{{--                                                </button>--}}
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
{{--                        @if($patients->hasPages())--}}
{{--                            <div class="d-flex justify-content-center mt-4">--}}
{{--                                {{ $patients->links() }}--}}
{{--                            </div>--}}
{{--                        @endif--}}
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Recherche en temps réel
            $('#searchPatient').on('keyup', function() {
                var searchValue = $(this).val().toLowerCase();
                var groupeFilter = $('#filterGroupeSanguin').val();

                filterPatients(searchValue, groupeFilter);
            });

            // Filtre par groupe sanguin
            $('#filterGroupeSanguin').on('change', function() {
                var searchValue = $('#searchPatient').val().toLowerCase();
                var groupeFilter = $(this).val();

                filterPatients(searchValue, groupeFilter);
            });

            // Effacer la recherche
            $('#clearSearch').on('click', function() {
                $('#searchPatient').val('');
                filterPatients('', $('#filterGroupeSanguin').val());
            });

            function filterPatients(searchValue, groupeFilter) {
                $('.patient-row').each(function() {
                    var rowSearch = $(this).data('search');
                    var rowGroupe = $(this).data('groupe');

                    var matchSearch = rowSearch.indexOf(searchValue) > -1;
                    var matchGroupe = !groupeFilter || rowGroupe === groupeFilter;

                    if (matchSearch && matchGroupe) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });

                // Afficher un message si aucun résultat
                var visibleRows = $('.patient-row:visible').length;
                if (visibleRows === 0) {
                    $('#noResults').show();
                } else {
                    $('#noResults').hide();
                }
            }

            // Confirmation de suppression
            window.confirmDelete = function(patientId) {
                if (confirm('Êtes-vous sûr de vouloir supprimer ce patient ?\nCette action est irréversible.')) {
                    // Créer un formulaire de suppression dynamique
                    var form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '/patient/patients/' + patientId;

                    var csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = '{{ csrf_token() }}';

                    var methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';

                    form.appendChild(csrfInput);
                    form.appendChild(methodInput);
                    document.body.appendChild(form);
                    form.submit();
                }
            };
        });
    </script>

    <style>
        .avatar-sm {
            width: 40px;
            height: 40px;
            min-width: 40px;
        }

        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
            cursor: pointer;
        }

        .patient-row {
            transition: all 0.2s ease;
        }

        .btn-group .btn {
            margin-right: 2px;
            border-radius: 4px !important;
        }

        #noResults {
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @media (max-width: 768px) {
            .btn-group {
                display: flex;
                flex-direction: column;
            }

            .btn-group .btn {
                margin: 2px 0;
            }
        }
    </style>
@endsection
