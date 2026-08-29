@extends('layouts.master')

@section('content')
<div class="page-content">
    <div class="container-fluid">
        @php
            $patient = $consultation->patient;
            $dossier = $patient->dossierMedical;
            $hasDossier = $dossier && (!empty($dossier->notes_generales) || $dossier->antecedentsMedicaux->isNotEmpty());
        @endphp

        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div>
                <h3 class="fw-bold mb-1 text-dark">Reprendre la consultation</h3>
                <p class="text-muted mb-0 small">
                    <i class="bi bi-clipboard-pulse me-1"></i>
                    Consultation #{{ $consultation->id }} — {{ $consultation->patient->prenom }} {{ $consultation->patient->nom }}
                </p>
            </div>
            <div class="d-flex flex-wrap align-items-center gap-2">
                @if($hasDossier)
                    <button type="button" class="btn btn-outline-primary shadow-sm d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#voirDossierModal">
                        <i class="bi bi-folder2-open"></i> Consulter le dossier médical
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill">
                            {{ $dossier->antecedentsMedicaux->count() }} antécédent(s)
                        </span>
                    </button>
                @else
                    <button type="button" class="btn btn-warning text-dark shadow-sm fw-semibold d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#interrogatoireModal">
                        <i class="bi bi-clipboard2-pulse"></i> Interrogatoire (Créer dossier)
                    </button>
                @endif
                <a href="{{ route('medecin.consultations.show', $consultation) }}" class="btn btn-light border shadow-sm">
                    <i class="bi bi-x-lg me-1"></i> Annuler
                </a>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-primary bg-opacity-10 border-0 py-3 px-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold fs-4 shadow-sm" style="width: 48px; height: 48px;">
                                {{ strtoupper(substr($consultation->patient->prenom, 0, 1)) }}{{ strtoupper(substr($consultation->patient->nom, 0, 1)) }}
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold text-dark fs-6">{{ $consultation->patient->prenom }} {{ $consultation->patient->nom }}</h6>
                                <span class="badge bg-white text-muted border small mt-1">Patient</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <ul class="list-unstyled mb-0 d-flex flex-column gap-3 small">
                            <li class="d-flex justify-content-between">
                                <span class="text-muted"><i class="bi bi-gender-ambiguous text-primary me-2"></i>Sexe :</span>
                                <span class="fw-semibold text-dark">{{ ucfirst($consultation->patient->sexe ?? 'Non spécifié') }}</span>
                            </li>
                            <li class="d-flex justify-content-between">
                                <span class="text-muted"><i class="bi bi-calendar3 text-primary me-2"></i>Naissance :</span>
                                <span class="fw-semibold text-dark">{{ optional($consultation->patient->date_naissance)->format('d/m/Y') ?? '—' }} ({{ optional($consultation->patient->date_naissance)->age ?? '—' }} ans)</span>
                            </li>
                            <li class="d-flex justify-content-between">
                                <span class="text-muted"><i class="bi bi-telephone text-primary me-2"></i>Téléphone :</span>
                                <a href="tel:{{ $consultation->patient->telephone }}" class="text-decoration-none fw-semibold text-dark">{{ $consultation->patient->telephone }}</a>
                            </li>
                        </ul>
                        <hr class="my-3">
                        <span class="text-muted small fw-semibold d-block mb-1">
                            <i class="bi bi-chat-square-text text-primary me-1"></i> Motif :
                        </span>
                        <div class="p-3 bg-light rounded-3 border">
                            <p class="mb-0 text-dark small fw-medium">
                                {{ $consultation->isDirecte() ? ($consultation->motif_direct ?? 'Non spécifié') : ($consultation->rendezVous->motif ?? 'Non spécifié') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-white border-bottom py-3 px-4">
                        <h5 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                            <i class="bi bi-clipboard-pulse text-primary"></i> Saisie de la consultation
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('medecin.consultations.update', $consultation) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label fw-bold text-dark small text-uppercase">
                                        <i class="bi bi-activity text-primary me-1"></i> Diagnostic
                                    </label>
                                    <textarea name="diagnostic" class="form-control" rows="2" placeholder="Diagnostic médical retenu...">{{ old('diagnostic', $consultation->diagnostic) }}</textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-secondary small text-uppercase">
                                        <i class="bi bi-eye text-secondary me-1"></i> Observations cliniques
                                    </label>
                                    <textarea name="observations" class="form-control" rows="3" placeholder="Signes vitaux, examen physique...">{{ old('observations', $consultation->observations) }}</textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-success small text-uppercase">
                                        <i class="bi bi-capsule-pill text-success me-1"></i> Traitement immédiat
                                    </label>
                                    <textarea name="traitement" class="form-control" rows="3" placeholder="Traitements prescrits ou soins administrés...">{{ old('traitement', $consultation->traitement) }}</textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold text-secondary small text-uppercase">
                                        <i class="bi bi-chat-left-quote text-secondary me-1"></i> Recommandations & Conseils
                                    </label>
                                    <textarea name="recommandations" class="form-control" rows="2" placeholder="Hygiène de vie, repos, date de contrôle...">{{ old('recommandations', $consultation->recommandations) }}</textarea>
                                </div>
                            </div>

                            <hr class="my-4">
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

        @if($hasDossier)
            <div class="modal fade" id="voirDossierModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                        <div class="modal-header bg-primary text-white py-3 px-4">
                            <div>
                                <h5 class="modal-title fw-bold text-white mb-0">Dossier medical : {{ $patient->prenom }} {{ $patient->nom }}</h5>
                                <small class="text-white-50">Notes et antecedents du patient</small>
                            </div>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body p-4 bg-light">
                            @if($dossier->notes_generales)
                                <div class="card border-0 shadow-sm rounded-3 mb-4">
                                    <div class="card-header bg-white fw-bold">Notes generales</div>
                                    <div class="card-body bg-white" style="white-space: pre-line;">{{ $dossier->notes_generales }}</div>
                                </div>
                            @endif
                            <div class="card border-0 shadow-sm rounded-3">
                                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                    <span class="fw-bold">Antecedents medicaux</span>
                                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#interrogatoireModal" data-bs-dismiss="modal">
                                        <i class="bi bi-pencil me-1"></i> Modifier / ajouter
                                    </button>
                                </div>
                                <div class="card-body bg-white p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0">
                                            <thead class="table-light">
                                                <tr><th>Type</th><th>Nom</th><th>Date</th><th>Gravite</th><th>Statut</th></tr>
                                            </thead>
                                            <tbody>
                                                @forelse($dossier->antecedentsMedicaux as $antecedent)
                                                    <tr>
                                                        <td>{{ ucfirst($antecedent->type) }}</td>
                                                        <td class="fw-semibold">{{ $antecedent->nom }}</td>
                                                        <td>{{ optional($antecedent->date_evenement)->format('d/m/Y') ?? '-' }}</td>
                                                        <td>{{ ucfirst($antecedent->gravite ?? '-') }}</td>
                                                        <td><span class="badge {{ $antecedent->actif ? 'bg-success' : 'bg-secondary' }}">{{ $antecedent->actif ? 'Actif' : 'Inactif' }}</span></td>
                                                    </tr>
                                                @empty
                                                    <tr><td colspan="5" class="text-center py-4 text-muted">Aucun antecedent repertorie.</td></tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer bg-white">
                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Poursuivre la consultation</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="modal fade" id="interrogatoireModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="modal-header bg-warning text-dark py-3 px-4">
                        <h5 class="modal-title fw-bold">Interrogatoire medical & antecedents</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4 bg-light">
                        <div class="card border-0 shadow-sm rounded-3 mb-4">
                            <div class="card-header bg-white fw-bold">Notes generales</div>
                            <div class="card-body bg-white">
                                <form method="POST" action="{{ route('medecin.patients.dossierMedical.update', $patient) }}">
                                    @csrf
                                    @method('PUT')
                                    <textarea class="form-control mb-3" rows="4" name="notes_generales" placeholder="Mode de vie, terrain familial, habitudes...">{{ old('notes_generales', $dossier->notes_generales ?? '') }}</textarea>
                                    <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-save me-1"></i> Enregistrer les notes</button>
                                </form>
                            </div>
                        </div>
                        <div class="card border-0 shadow-sm rounded-3">
                            <div class="card-header bg-white fw-bold">Ajouter un antecedent</div>
                            <div class="card-body bg-white">
                                <form method="POST" action="{{ route('medecin.patients.antecedents.store', $patient) }}">
                                    @csrf
                                    <div id="editAntecedentsList">
                                        <div class="antecedent-entry row g-3 border rounded-3 p-3 mb-3">
                                            <div class="col-md-6"><label class="form-label small fw-semibold">Type</label><select name="antecedents[0][type]" class="form-select" required><option value="maladie">Maladie / Pathologie</option><option value="allergie">Allergie</option><option value="operation">Operation / Chirurgie</option><option value="vaccin">Vaccination</option><option value="familial">Antecedent familial</option><option value="autre">Autre</option></select></div>
                                            <div class="col-md-6"><label class="form-label small fw-semibold">Nom / Intitule</label><input type="text" name="antecedents[0][nom]" class="form-control" required></div>
                                            <div class="col-md-6"><label class="form-label small fw-semibold">Date / Periode</label><input type="date" name="antecedents[0][date_evenement]" class="form-control"></div>
                                            <div class="col-md-6"><label class="form-label small fw-semibold">Gravite</label><select name="antecedents[0][gravite]" class="form-select"><option value="">Non definie</option><option value="faible">Faible</option><option value="moyenne">Moyenne</option><option value="haute">Haute / Critique</option></select></div>
                                            <div class="col-12"><label class="form-label small fw-semibold">Description</label><input type="text" name="antecedents[0][description]" class="form-control"></div>
                                            <div class="col-12"><div class="form-check"><input class="form-check-input" type="checkbox" name="antecedents[0][actif]" value="1" checked><label class="form-check-label">Antecedent actuellement actif</label></div></div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-primary mb-3" id="addEditAntecedent"><i class="bi bi-plus-circle me-1"></i> Ajouter un autre antecedent</button>
                                    <div><button type="submit" class="btn btn-success btn-sm"><i class="bi bi-save me-1"></i> Enregistrer les antecedents</button></div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-white"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer et poursuivre</button></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const list = document.getElementById('editAntecedentsList');
        const addButton = document.getElementById('addEditAntecedent');
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