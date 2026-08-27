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
                                <i class="ri-team-line me-2 text-primary"></i>
                                Gestion des Patients
                            </h4>
                            <p class="text-muted mb-0">
                                <i class="ri-information-line me-1"></i>
                                {{ $patients->count() }} patient(s) enregistré(s)
                            </p>
                        </div>
                        <a href="{{ route('secretaire.patients.create') }}" class="btn btn-primary">
                            <i class="ri-user-add-line me-1"></i> Nouveau Patient
                        </a>
                    </div>
                </div>
            </div>

            <!-- Filtres et recherche -->
            <div class="row mb-3">
                <div class="col-md-4">
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
                    <select id="filterMedecin" class="form-select">
                        <option value="">Tous les médecins</option>
                        @foreach($medecins ?? [] as $medecin)
                            <option value="{{ $medecin->id }}">Dr. {{ $medecin->user->name }}</option>
                        @endforeach
                    </select>
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
                <div class="col-md-2">
                    <select id="filterStatut" class="form-select">
                        <option value="">Tous</option>
                        <option value="avec_medecin">Avec médecin</option>
                        <option value="sans_medecin">Sans médecin</option>
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
                            <a href="{{ route('secretaire.patients.create') }}" class="btn btn-primary mt-2">
                                <i class="ri-user-add-line me-1"></i> Ajouter un patient
                            </a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle" id="patientsTable">
                                <thead class="table-light">
                                <tr>
                                    <th style="width: 5%;">#</th>
                                    <th style="width: 20%;">
                                        <i class="ri-user-line me-1"></i>Patient
                                    </th>
                                    <th style="width: 15%;">
                                        <i class="ri-phone-line me-1"></i>Contact
                                    </th>
                                    <th style="width: 12%;">
                                        <i class="ri-calendar-line me-1"></i>Naissance
                                    </th>
                                    <th style="width: 10%;">
                                        <i class="ri-drop-line me-1"></i>Groupe
                                    </th>
                                    <th style="width: 23%;">
                                        <i class="ri-stethoscope-line me-1"></i>Médecin(s)
                                    </th>
                                    <th style="width: 15%;" class="text-end">
                                        Actions
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($patients as $index => $patient)
                                    <tr class="patient-row"
                                        data-search="{{ strtolower($patient->prenom . ' ' . $patient->nom . ' ' . $patient->telephone) }}"
                                        data-medecin="{{ $patient->medecins->pluck('id')->implode(',') }}"
                                        data-groupe="{{ $patient->groupe_sanguin ?? '' }}"
                                        data-statut="{{ $patient->medecins->count() > 0 ? 'avec_medecin' : 'sans_medecin' }}">
                                        <td></td>
{{--                                        {{ ($patients->currentPage() - 1) * $patients->perPage() + $index + 1 }}--}}
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-primary-subtle rounded-circle d-flex align-items-center justify-content-center me-3">
                                                    <span class="text-primary fw-bold">
                                                        {{ strtoupper(substr($patient->prenom, 0, 1)) }}{{ strtoupper(substr($patient->nom, 0, 1)) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <strong>{{ $patient->prenom }} {{ $patient->nom }}</strong>
                                                    <br>
                                                    <small class="text-muted">ID: #{{ $patient->id }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <i class="ri-phone-line me-1 text-muted"></i>
                                                {{ $patient->telephone ?? '—' }}
                                            </div>
                                            @if($patient->email)
                                                <small class="text-muted">
                                                    <i class="ri-mail-line me-1"></i>
                                                    {{ $patient->email }}
                                                </small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($patient->date_naissance)
                                                <strong>{{ $patient->date_naissance->format('d/m/Y') }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $patient->date_naissance->age }} ans</small>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($patient->groupe_sanguin)
                                                <span class="badge bg-danger-subtle text-danger fs-6">
                                                    <i class="ri-drop-fill me-1"></i>{{ $patient->groupe_sanguin }}
                                                </span>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($patient->medecins->count() > 0)
                                                <div class="d-flex flex-wrap gap-1">
                                                    @foreach($patient->medecins->take(2) as $med)
                                                        <span class="badge bg-success-subtle text-success">
                                                            <i class="ri-user-star-line me-1"></i>
                                                            Dr. {{ $med->user->name }}
                                                        </span>
                                                    @endforeach
                                                    @if($patient->medecins->count() > 2)
                                                        <span class="badge bg-secondary">
                                                            +{{ $patient->medecins->count() - 2 }} autre(s)
                                                        </span>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="badge bg-warning-subtle text-warning">
                                                    <i class="ri-alert-line me-1"></i>
                                                    Aucun médecin
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <div class="btn-group">
                                                <a href="{{ route('secretaire.patients.show', $patient) }}"
                                                   class="btn btn-sm btn-outline-primary"
                                                   title="Voir les détails"
                                                   data-bs-toggle="tooltip">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                                <a href="{{ route('secretaire.patients.edit', $patient) }}"
                                                   class="btn btn-sm btn-outline-warning"
                                                   title="Modifier"
                                                   data-bs-toggle="tooltip">
                                                    <i class="ri-edit-line"></i>
                                                </a>
{{--                                                <a href=""--}}
{{--                                                   class="btn btn-sm btn-outline-success"--}}
{{--                                                   title="Dossier médical"--}}
{{--                                                   data-bs-toggle="tooltip">--}}
{{--                                                    <i class="ri-folder-user-line"></i>--}}
{{--                                                </a>--}}
{{--                                                <button type="button"--}}
{{--                                                        class="btn btn-sm btn-outline-danger"--}}
{{--                                                        onclick="confirmDelete({{ $patient->id }})"--}}
{{--                                                        title="Supprimer"--}}
{{--                                                        data-bs-toggle="tooltip">--}}
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
{{--                            <div class="d-flex justify-content-between align-items-center mt-3">--}}
{{--                                <div class="text-muted">--}}
{{--                                    Affichage de {{ $patients->firstItem() ?? 0 }} à {{ $patients->lastItem() ?? 0 }} sur {{ $patients->total() }} patients--}}
{{--                                </div>--}}
{{--                                <div>--}}
{{--                                    {{ $patients->links() }}--}}
{{--                                </div>--}}
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
            // Initialiser les tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            // Recherche en temps réel
            $('#searchPatient').on('keyup', function() {
                filterPatients();
            });

            // Filtres
            $('#filterMedecin, #filterGroupeSanguin, #filterStatut').on('change', function() {
                filterPatients();
            });

            // Effacer la recherche
            $('#clearSearch').on('click', function() {
                $('#searchPatient').val('');
                filterPatients();
            });

            function filterPatients() {
                var searchValue = $('#searchPatient').val().toLowerCase();
                var medecinFilter = $('#filterMedecin').val();
                var groupeFilter = $('#filterGroupeSanguin').val();
                var statutFilter = $('#filterStatut').val();

                $('.patient-row').each(function() {
                    var rowSearch = $(this).data('search');
                    var rowMedecin = $(this).data('medecin').toString();
                    var rowGroupe = $(this).data('groupe');
                    var rowStatut = $(this).data('statut');

                    var matchSearch = rowSearch.indexOf(searchValue) > -1;
                    var matchMedecin = !medecinFilter || rowMedecin.split(',').includes(medecinFilter);
                    var matchGroupe = !groupeFilter || rowGroupe === groupeFilter;
                    var matchStatut = !statutFilter || rowStatut === statutFilter;

                    if (matchSearch && matchMedecin && matchGroupe && matchStatut) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });

                // Afficher un message si aucun résultat
                var visibleRows = $('.patient-row:visible').length;
                if (visibleRows === 0) {
                    if ($('#noResults').length === 0) {
                        $('tbody').append('<tr id="noResults"><td colspan="7" class="text-center py-4">Aucun patient trouvé</td></tr>');
                    }
                } else {
                    $('#noResults').remove();
                }
            }

            // Confirmation de suppression
            window.confirmDelete = function(patientId) {
                Swal.fire({
                    title: 'Êtes-vous sûr ?',
                    text: "Cette action est irréversible !",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Oui, supprimer',
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (result.isConfirmed) {
                        var form = document.createElement('form');
                        form.method = 'POST';
                        form.action = '/secretaire/patients/' + patientId;

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
                });
            };
        });
    </script>

    <style>
        .avatar-sm {
            width: 40px;
            height: 40px;
            min-width: 40px;
        }

        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }

        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
        }

        .btn-group .btn {
            margin-right: 2px;
            border-radius: 4px !important;
        }

        .badge {
            font-weight: 500;
        }

        @media (max-width: 768px) {
            .d-flex.justify-content-between {
                flex-direction: column;
                align-items: flex-start;
            }

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
