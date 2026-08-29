@extends('layouts.master')

@section('content')
<div class="page-content">
    <div class="container-fluid">

        @php
            $patient = $rendezVous->patient;
            $dossier = $patient->dossierMedical;
            // On considère que le dossier existe s'il y a des notes ou au moins un antécédent
            $hasDossier = $dossier && (!empty($dossier->notes_generales) || $dossier->antecedentsMedicaux->isNotEmpty());
        @endphp

        <!-- En-tête de la page -->
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div>
                <h3 class="fw-bold mb-1 text-dark">Nouvelle consultation</h3>
                <p class="text-muted mb-0 small">
                    <i class="bi bi-calendar-check me-1"></i> Issue du rendez-vous #{{ $rendezVous->id }} &bull; {{ now()->translatedFormat('l d F Y') }}
                </p>
            </div>

            <div class="d-flex align-items-center gap-2">
                <!-- Bouton Dynamique Dossier / Interrogatoire -->
                @if($hasDossier)
                    <button type="button" class="btn btn-outline-primary shadow-sm d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#voirDossierModal">
                        <i class="bi bi-folder2-open fs-5 text-primary"></i>
                        <span>Consulter le dossier médical</span>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill">
                            {{ $dossier->antecedentsMedicaux->count() }} antécédent(s)
                        </span>
                    </button>
                @else
                    <button type="button" class="btn btn-warning text-dark shadow-sm fw-semibold d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#interrogatoireModal">
                        <i class="bi bi-clipboard2-pulse fs-5"></i>
                        <span>Interrogatoire (Créer dossier)</span>
                        <span class="badge bg-danger text-white rounded-pill">Requis / Vierge</span>
                    </button>
                @endif

                <a href="{{ route('medecin.consultations.index') }}" class="btn btn-light border shadow-sm">
                    <i class="bi bi-x-lg me-1"></i> Annuler
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-4">
            <!-- Colonne Gauche : Résumé Patient & Motif -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                    <div class="card-header bg-primary bg-opacity-10 border-0 py-3 px-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold fs-4 shadow-sm" style="width: 48px; height: 48px;">
                                {{ strtoupper(substr($patient->prenom, 0, 1)) }}{{ strtoupper(substr($patient->nom, 0, 1)) }}
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold text-dark fs-6">{{ $patient->prenom }} {{ $patient->nom }}</h6>
                                <span class="badge bg-white text-muted border small mt-1">Patient</span>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <ul class="list-unstyled mb-0 d-flex flex-column gap-3 small">
                            <li class="d-flex justify-content-between">
                                <span class="text-muted"><i class="bi bi-gender-ambiguous text-primary me-2"></i>Sexe :</span>
                                <span class="fw-semibold text-dark">{{ ucfirst($patient->sexe ?? 'Non spécifié') }}</span>
                            </li>
                            <li class="d-flex justify-content-between">
                                <span class="text-muted"><i class="bi bi-calendar3 text-primary me-2"></i>Naissance :</span>
                                <span class="fw-semibold text-dark">{{ optional($patient->date_naissance)->format('d/m/Y') ?? '—' }} ({{ optional($patient->date_naissance)->age ?? '—' }} ans)</span>
                            </li>
                            <li class="d-flex justify-content-between">
                                <span class="text-muted"><i class="bi bi-droplet text-danger me-2"></i>Groupe sanguin :</span>
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle fw-bold">{{ $patient->groupe_sanguin ?? 'N/A' }}</span>
                            </li>
                            <li class="d-flex justify-content-between">
                                <span class="text-muted"><i class="bi bi-telephone text-primary me-2"></i>Téléphone :</span>
                                <a href="tel:{{ $patient->telephone }}" class="text-decoration-none fw-semibold text-dark">{{ $patient->telephone }}</a>
                            </li>
                        </ul>

                        <hr class="my-3">

                        <div>
                            <span class="text-muted small fw-semibold d-block mb-1">
                                <i class="bi bi-chat-square-text text-primary me-1"></i> Motif de la consultation :
                            </span>
                            <div class="p-3 bg-light rounded-3 border">
                                <p class="mb-0 text-dark small fw-medium">{{ $rendezVous->motif ?: 'Non précisé lors de la prise de RDV.' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Aperçu rapide des antécédents actifs -->
                @if($hasDossier && $dossier->antecedentsMedicaux->where('actif', true)->isNotEmpty())
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-header bg-white border-bottom py-3 px-4">
                            <h6 class="mb-0 fw-bold text-danger d-flex align-items-center gap-2 small text-uppercase">
                                <i class="bi bi-exclamation-triangle-fill"></i> Antécédents Actifs / Allergies
                            </h6>
                        </div>
                        <div class="card-body p-3">
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($dossier->antecedentsMedicaux->where('actif', true) as $ant)
                                    <span class="badge {{ $ant->type === 'allergie' ? 'bg-danger' : 'bg-warning text-dark' }} p-2 rounded-2">
                                        {{ ucfirst($ant->type) }} : {{ $ant->nom }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Colonne Droite : Formulaire Consultation -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-white border-bottom py-3 px-4">
                        <h5 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                            <i class="bi bi-clipboard-pulse text-primary"></i> Saisie de la consultation
                        </h5>
                    </div>

                    <div class="card-body p-4">
                        <form action="{{ route('medecin.consultations.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="rendez_vous_id" value="{{ $rendezVous->id }}">

                            <div class="row g-3">
                                <!-- Diagnostic -->
                                <div class="col-12">
                                    <label class="form-label fw-bold text-dark small text-uppercase">
                                        <i class="bi bi-activity text-primary me-1"></i> Diagnostic
                                    </label>
                                    <textarea name="diagnostic" class="form-control" rows="2" placeholder="Diagnostic médical retenu...">{{ old('diagnostic') }}</textarea>
                                </div>

                                <!-- Observations -->
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-secondary small text-uppercase">
                                        <i class="bi bi-eye text-secondary me-1"></i> Observations cliniques
                                    </label>
                                    <textarea name="observations" class="form-control" rows="3" placeholder="Signes vitaux, examen physique...">{{ old('observations') }}</textarea>
                                </div>

                                <!-- Traitement -->
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-success small text-uppercase">
                                        <i class="bi bi-capsule-pill text-success me-1"></i> Traitement immédiat
                                    </label>
                                    <textarea name="traitement" class="form-control" rows="3" placeholder="Traitements prescrits ou soins administrés...">{{ old('traitement') }}</textarea>
                                </div>

                                <!-- Recommandations -->
                                <div class="col-12">
                                    <label class="form-label fw-bold text-secondary small text-uppercase">
                                        <i class="bi bi-chat-left-quote text-secondary me-1"></i> Recommandations & Conseils
                                    </label>
                                    <textarea name="recommandations" class="form-control" rows="2" placeholder="Hygiène de vie, repos, date de contrôle...">{{ old('recommandations') }}</textarea>
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Boutons d'action -->
                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                                <button type="submit" name="action" value="continuer" class="btn btn-outline-secondary px-4">
                                    <i class="bi bi-save me-1"></i> Enregistrer et poursuivre plus tard
                                </button>
                                <button type="submit" name="action" value="terminer" class="btn btn-success btn-lg px-4 fw-semibold shadow-sm d-flex align-items-center gap-2">
                                    <i class="bi bi-check2-circle"></i> Terminer la consultation
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL 1 : VOIR LE DOSSIER MÉDICAL (Quand le dossier existe)               -->
<!-- ========================================================================= -->
@if($hasDossier)
<div class="modal fade" id="voirDossierModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-primary text-white py-3 px-4">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-folder2-open fs-4"></i>
                    <div>
                        <h5 class="modal-title fw-bold text-white mb-0">Dossier Médical : {{ $patient->prenom }} {{ $patient->nom }}</h5>
                        <small class="text-white-50">Consultation des antécédents et antériorités</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4 bg-light">
                <!-- Notes générales -->
                @if(!empty($dossier->notes_generales))
                    <div class="card border-0 shadow-2xs rounded-3 mb-4">
                        <div class="card-header bg-white py-3 px-4 border-bottom">
                            <h6 class="fw-bold text-dark mb-0"><i class="bi bi-journal-text text-primary me-2"></i>Notes Générales du dossier</h6>
                        </div>
                        <div class="card-body p-4 bg-white">
                            <p class="mb-0 text-secondary" style="white-space: pre-line;">{{ $dossier->notes_generales }}</p>
                        </div>
                    </div>
                @endif

                <!-- Antécédents -->
                <div class="card border-0 shadow-2xs rounded-3 mb-4">
                    <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold text-dark mb-0"><i class="bi bi-shield-plus text-primary me-2"></i>Antécédents Médicaux</h6>
                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#interrogatoireModal">
                            <i class="bi bi-pencil me-1"></i> Modifier / Ajouter
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light small text-uppercase text-muted">
                                    <tr>
                                        <th>Type</th>
                                        <th>Nom</th>
                                        <th>Date</th>
                                        <th>Gravité</th>
                                        <th>Statut</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($dossier->antecedentsMedicaux as $antecedent)
                                        <tr>
                                            <td><span class="badge bg-light text-dark border">{{ ucfirst($antecedent->type) }}</span></td>
                                            <td class="fw-semibold text-dark">{{ $antecedent->nom }}</td>
                                            <td>{{ optional($antecedent->date_evenement)->format('d/m/Y') ?? '—' }}</td>
                                            <td>
                                                <span class="badge bg-{{ $antecedent->gravite === 'haute' ? 'danger' : ($antecedent->gravite === 'moyenne' ? 'warning' : 'secondary') }}-subtle text-{{ $antecedent->gravite === 'haute' ? 'danger' : ($antecedent->gravite === 'moyenne' ? 'warning text-dark' : 'secondary') }}">
                                                    {{ ucfirst($antecedent->gravite ?? 'Non précisée') }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($antecedent->actif)
                                                    <span class="badge bg-success-subtle text-success"><i class="bi bi-check-circle me-1"></i>Actif</span>
                                                @else
                                                    <span class="badge bg-secondary-subtle text-secondary">Inactif</span>
                                                @endif
                                            </td>
                                            <td class="small text-muted">{{ $antecedent->description ?? '—' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4 text-muted">Aucun antécédent répertorié.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer bg-white border-top px-4 py-3">
                <button type="button" class="btn btn-primary px-4 fw-semibold" data-bs-dismiss="modal">
                    Poursuivre la consultation <i class="bi bi-arrow-right ms-1"></i>
                </button>
            </div>
        </div>
    </div>
</div>
@endif

<!-- ========================================================================= -->
<!-- MODAL 2 : INTERROGATOIRE / CRÉATION OU MISE À JOUR DU DOSSIER             -->
<!-- ========================================================================= -->
<div class="modal fade" id="interrogatoireModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-warning text-dark py-3 px-4">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-clipboard2-pulse fs-4"></i>
                    <div>
                        <h5 class="modal-title fw-bold mb-0">Interrogatoire Médical & Antécédents</h5>
                        <small class="text-dark-50">Patient : {{ $patient->prenom }} {{ $patient->nom }}</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4 bg-light">
                
                <!-- 1. Notes Générales -->
                <div class="card border-0 shadow-2xs rounded-3 mb-4">
                    <div class="card-header bg-white py-3 px-4 border-bottom">
                        <h6 class="fw-bold text-dark mb-0"><i class="bi bi-journal-text text-primary me-2"></i>1. Notes Générales / Terrain Patient</h6>
                    </div>
                    <div class="card-body p-4 bg-white">
                        <form method="POST" action="{{ route('medecin.patients.dossierMedical.update', $patient) }}">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <textarea class="form-control" rows="3" name="notes_generales" placeholder="Renseignez le mode de vie, terrain familial, habitudes, régimes...">{{ old('notes_generales', $dossier->notes_generales ?? '') }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="bi bi-save me-1"></i> Enregistrer les notes
                            </button>
                        </form>
                    </div>
                </div>

                <!-- 2. Ajouter un antécédent -->
                <div class="card border-0 shadow-2xs rounded-3">
                    <div class="card-header bg-white py-3 px-4 border-bottom">
                        <h6 class="fw-bold text-dark mb-0"><i class="bi bi-plus-circle text-primary me-2"></i>2. Ajouter un Antécédent (Maladie, Allergie, Chirurgie...)</h6>
                    </div>
                    <div class="card-body p-4 bg-white">
                        <form method="POST" action="{{ route('medecin.patients.antecedents.store', $patient) }}">
                            @csrf
                            <div id="createAntecedentsList">
                                <div class="antecedent-entry row g-3 border rounded-3 p-3 mb-3">
                                    <div class="col-md-4"><label class="form-label small fw-semibold">Type <span class="text-danger">*</span></label><select name="antecedents[0][type]" class="form-select form-select-sm" required><option value="maladie">Maladie / Pathologie</option><option value="allergie">Allergie</option><option value="operation">Opération / Chirurgie</option><option value="vaccin">Vaccination</option><option value="familial">Antécédent familial</option><option value="autre">Autre</option></select></div>
                                    <div class="col-md-4"><label class="form-label small fw-semibold">Nom / Intitulé <span class="text-danger">*</span></label><input type="text" name="antecedents[0][nom]" class="form-control form-control-sm" required></div>
                                    <div class="col-md-4"><label class="form-label small fw-semibold">Date / Période</label><input type="date" name="antecedents[0][date_evenement]" class="form-control form-control-sm"></div>
                                    <div class="col-md-4"><label class="form-label small fw-semibold">Gravité</label><select name="antecedents[0][gravite]" class="form-select form-select-sm"><option value="">Non définie</option><option value="faible">Faible</option><option value="moyenne">Moyenne</option><option value="haute">Haute / Critique</option></select></div>
                                    <div class="col-md-8"><label class="form-label small fw-semibold">Précisions / Description</label><input type="text" name="antecedents[0][description]" class="form-control form-control-sm"></div>
                                    <div class="col-12"><div class="form-check"><input class="form-check-input" type="checkbox" name="antecedents[0][actif]" value="1" checked><label class="form-check-label small">Antécédent actuellement actif</label></div></div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary mb-3" id="addCreateAntecedent"><i class="bi bi-plus-circle me-1"></i> Ajouter un autre antécédent</button>
                            <div class="text-end"><button type="submit" class="btn btn-sm btn-success px-3"><i class="bi bi-save me-1"></i> Enregistrer les antécédents</button></div>
                        </form>
                    </div>
                </div>

            </div>

            <div class="modal-footer bg-white border-top px-4 py-3">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                    Fermer et poursuivre la consultation
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const list = document.getElementById('createAntecedentsList');
        const addButton = document.getElementById('addCreateAntecedent');
        let index = 1;
        if (!list || !addButton) return;
        addButton.addEventListener('click', function () {
            const entry = list.querySelector('.antecedent-entry').cloneNode(true);
            entry.querySelectorAll('input, select').forEach(function (field) {
                field.name = field.name.replace(/antecedents\[0\]/g, 'antecedents[' + index + ']');
                if (field.type === 'checkbox') field.checked = true;
                else if (field.tagName === 'SELECT') field.selectedIndex = 0;
                else field.value = '';
            });
            list.appendChild(entry);
            index++;
        });
    });
</script>
@endsection