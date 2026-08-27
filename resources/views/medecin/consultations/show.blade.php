@extends('layouts.master')

@section('content')
<div class="page-content mt-4">
    <div class="container-fluid">
        
        <!-- En-tête de la page -->
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('medecin.consultations.index') }}" class="btn btn-light border rounded-circle shadow-sm p-2 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;" title="Retour aux consultations">
                    <i class="bi bi-arrow-left fs-5"></i>
                </a>
                <div>
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <h3 class="fw-bold mb-0 text-dark">Consultation #{{ $consultation->id }}</h3>
                        
                        <!-- Badge Statut -->
                        <span class="badge {{ $consultation->isTerminee() ? 'bg-success-subtle text-success border border-success' : 'bg-warning-subtle text-warning border border-warning' }} px-3 py-1 rounded-pill fw-medium">
                            <i class="bi {{ $consultation->isTerminee() ? 'bi-check-circle-fill' : 'bi-hourglass-split' }} me-1"></i>
                            {{ ucfirst($consultation->statut) }}
                        </span>

                        <!-- Badge Urgence -->
                        @if($consultation->isUrgence())
                            <span class="badge bg-danger-subtle text-danger border border-danger px-3 py-1 rounded-pill fw-medium">
                                <i class="bi bi-exclamation-octagon-fill me-1"></i> Urgence
                            </span>
                        @endif
                    </div>
                    <p class="text-muted mb-0 small mt-1">
                        <i class="bi bi-calendar-event me-1"></i> Effectuée le {{ $consultation->date_consultation->format('d/m/Y à H:i') }}
                    </p>
                </div>
            </div>

            <!-- Actions Rapides -->
            <div class="d-flex flex-wrap align-items-center gap-2">
                @if(!$consultation->isTerminee())
                    <a href="{{ route('medecin.consultations.edit', $consultation) }}" class="btn btn-primary shadow-sm d-flex align-items-center gap-2">
                        <i class="bi bi-pencil-square"></i> Reprendre la consultation
                    </a>
                @endif
                <button type="button" class="btn btn-outline-primary shadow-sm d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#prescrireOrdonnanceModal">
                    <i class="bi bi-capsule"></i> Prescrire une ordonnance
                </button>
                <button type="button" class="btn btn-outline-info text-info shadow-sm d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#demandeExamenModal">
                    <i class="bi bi-droplet-half"></i> Demande d'examens
                </button>
            </div>
        </div>

        <div class="row g-4">
            <!-- Colonne gauche : Patient & Origine -->
            <div class="col-lg-4">
                
                <!-- Carte Patient -->
                <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                    <div class="card-header bg-primary bg-opacity-10 border-0 py-3 px-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold fs-4 shadow-sm" style="width: 48px; height: 48px;">
                                {{ strtoupper(substr($consultation->patient->prenom, 0, 1)) }}{{ strtoupper(substr($consultation->patient->nom, 0, 1)) }}
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold text-dark fs-6">{{ $consultation->patient->prenom }} {{ $consultation->patient->nom }}</h6>
                                <span class="badge bg-white text-muted border small mt-1">Dossier Patient</span>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <ul class="list-unstyled mb-0 d-flex flex-column gap-3">
                            <li class="d-flex align-items-center justify-content-between">
                                <span class="text-muted small"><i class="bi bi-telephone text-primary me-2"></i>Téléphone</span>
                                <a href="tel:{{ $consultation->patient->telephone }}" class="fw-semibold text-dark text-decoration-none small">{{ $consultation->patient->telephone }}</a>
                            </li>
                            <li class="d-flex align-items-center justify-content-between">
                                <span class="text-muted small"><i class="bi bi-droplet text-danger me-2"></i>Groupe sanguin</span>
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle fw-bold">{{ $consultation->patient->groupe_sanguin ?? 'N/A' }}</span>
                            </li>
                            @if($consultation->patient->date_naissance)
                                <li class="d-flex align-items-center justify-content-between">
                                    <span class="text-muted small"><i class="bi bi-calendar3 text-primary me-2"></i>Naissance</span>
                                    <span class="fw-semibold text-dark small">{{ $consultation->patient->date_naissance->format('d/m/Y') }} ({{ $consultation->patient->date_naissance->age }} ans)</span>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>

                <!-- Carte Origine de la consultation -->
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-white border-bottom py-3 px-4">
                        <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                            <i class="bi bi-signpost-2 text-primary"></i> Origine de la consultation
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        @if($consultation->isDirecte())
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <span class="badge bg-secondary-subtle text-secondary border px-2 py-1">
                                    <i class="bi bi-box-arrow-in-right me-1"></i> Accueil direct
                                </span>
                            </div>
                            <div class="mb-2">
                                <span class="text-muted small fw-semibold">Motif déclaré :</span>
                                <p class="mb-0 text-dark small fw-medium">{{ $consultation->motif_direct ?? 'Non spécifié' }}</p>
                            </div>
                            @if($consultation->notes_accueil)
                                <div class="mt-3 p-3 bg-light rounded-3 border">
                                    <span class="text-muted small fw-semibold d-block mb-1">Notes de l'accueil :</span>
                                    <p class="mb-0 text-secondary small">{{ $consultation->notes_accueil }}</p>
                                </div>
                            @endif
                        @else
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">
                                    <i class="bi bi-calendar-check me-1"></i> RDV Planifié
                                </span>
                            </div>
                            <div class="mb-2">
                                <span class="text-muted small fw-semibold">Date du RDV :</span>
                                <p class="mb-0 text-dark small fw-medium">{{ $consultation->rendezVous->date_heure->format('d/m/Y à H:i') }}</p>
                            </div>
                           
                            <div class="mb-0">
                                <span class="text-muted small fw-semibold">Motif :</span>
                                <p class="mb-0 text-dark small fw-medium">{{ $consultation->rendezVous->motif ?? 'Non spécifié' }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Colonne droite : Compte-rendu Médical -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                    <div class="card-header bg-white border-bottom py-3 px-4">
                        <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                            <i class="bi bi-clipboard2-pulse text-primary"></i> Compte-rendu médical
                        </h6>
                    </div>

                    <div class="card-body p-4">
                        <div class="row g-4">
                            <!-- Diagnostic -->
                            <div class="col-12">
                                <div class="p-3 bg-primary-subtle bg-opacity-25 rounded-3 border border-primary-subtle">
                                    <h6 class="text-primary fw-bold mb-2 d-flex align-items-center gap-2 small text-uppercase">
                                        <i class="bi bi-activity"></i> Diagnostic
                                    </h6>
                                    <p class="mb-0 {{ !$consultation->diagnostic ? 'text-muted fst-italic' : 'text-dark fw-medium' }}">
                                        {{ $consultation->diagnostic ?? 'Aucun diagnostic renseigné.' }}
                                    </p>
                                </div>
                            </div>

                            <!-- Observations -->
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded-3 border h-100">
                                    <h6 class="text-secondary fw-bold mb-2 d-flex align-items-center gap-2 small text-uppercase">
                                        <i class="bi bi-eye"></i> Observations
                                    </h6>
                                    <p class="mb-0 {{ !$consultation->observations ? 'text-muted fst-italic' : 'text-secondary' }} small">
                                        {{ $consultation->observations ?? 'Aucune observation enregistrée.' }}
                                    </p>
                                </div>
                            </div>

                            <!-- Traitement -->
                            <div class="col-md-6">
                                <div class="p-3 bg-success-subtle bg-opacity-25 rounded-3 border border-success-subtle h-100">
                                    <h6 class="text-success fw-bold mb-2 d-flex align-items-center gap-2 small text-uppercase">
                                        <i class="bi bi-capsule-pill"></i> Traitement
                                    </h6>
                                    <p class="mb-0 {{ !$consultation->traitement ? 'text-muted fst-italic' : 'text-dark' }} small">
                                        {{ $consultation->traitement ?? 'Aucun traitement prescrit directement.' }}
                                    </p>
                                </div>
                            </div>

                            <!-- Recommandations -->
                            <div class="col-12">
                                <div class="p-3 bg-light rounded-3 border">
                                    <h6 class="text-secondary fw-bold mb-2 d-flex align-items-center gap-2 small text-uppercase">
                                        <i class="bi bi-chat-left-quote"></i> Recommandations & Conseils
                                    </h6>
                                    <p class="mb-0 {{ !$consultation->recommandations ? 'text-muted fst-italic' : 'text-secondary' }} small">
                                        {{ $consultation->recommandations ?? 'Aucune recommandation formulée.' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Onglets Ordonnances & Examens -->
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-white border-bottom p-0">
                        <ul class="nav nav-tabs nav-tabs-custom border-bottom-0 px-4 pt-2" id="dossierTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link py-3 fw-semibold text-secondary d-flex align-items-center gap-2 border-0" id="ordonnances-tab" data-bs-toggle="tab" data-bs-target="#ordonnances-pane" type="button" role="tab">
                                    <i class="bi bi-prescription2 text-primary fs-5"></i> Ordonnances
                                    <span class="badge bg-primary-subtle text-primary rounded-pill ms-1">{{ $ordonnances->total() ?? $ordonnances->count() }}</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link py-3 fw-semibold text-secondary d-flex align-items-center gap-2 border-0" id="examens-tab" data-bs-toggle="tab" data-bs-target="#examens-pane" type="button" role="tab">
                                    <i class="bi bi-eyedropper text-info fs-5"></i> Demandes d'examens
                                    <span class="badge bg-info-subtle text-info rounded-pill ms-1">{{ $demandesExamens->total() ?? $demandesExamens->count() }}</span>
                                </button>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body p-0">
                        <div class="tab-content" id="dossierTabsContent">
                            
                            <!-- ===== Onglet Ordonnances ===== -->
                            <div class="tab-pane fade p-4" id="ordonnances-pane" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light">
                                            <tr class="small text-uppercase text-muted">
                                                <th>N°</th>
                                                <th>Date</th>
                                                <th>Médecin</th>
                                                <th class="text-center">Médicaments</th>
                                                <th class="text-end">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($ordonnances as $ordonnance)
                                                <tr>
                                                    <td class="fw-semibold text-dark">#{{ $ordonnance->id }}</td>
                                                    <td>{{ $ordonnance->date_prescription->format('d/m/Y') }}</td>
                                                    <td>{{ $ordonnance->medecin->user->name }}</td>
                                                    <td class="text-center">
                                                        <span class="badge bg-light text-dark border">
                                                            <i class="bi bi-capsule me-1 text-primary"></i>{{ $ordonnance->details->count() }}
                                                        </span>
                                                    </td>
                                                    <td class="text-end">
                                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#ordonnanceModal{{ $ordonnance->id }}">
                                                            <i class="bi bi-eye me-1"></i> Détails
                                                        </button>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center py-5 text-muted">
                                                        <i class="bi bi-prescription2 fs-1 text-muted opacity-50 d-block mb-2"></i>
                                                        Aucune ordonnance rédigée pour cette consultation.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                @if($ordonnances->hasPages())
                                    <div class="mt-3 px-3">
                                        {{ $ordonnances->appends(request()->except('ordonnances_page'))->links() }}
                                    </div>
                                @endif
                            </div>

                            <!-- ===== Onglet Examens ===== -->
                            <div class="tab-pane fade p-4" id="examens-pane" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light">
                                            <tr class="small text-uppercase text-muted">
                                                <th>N°</th>
                                                <th>Date demande</th>
                                                <th>Statut</th>
                                                <th class="text-center">Total examens</th>
                                                <th class="text-end">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($demandesExamens as $demande)
                                                <tr>
                                                    <td class="fw-semibold text-dark">#{{ $demande->id }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($demande->date_demande)->format('d/m/Y') }}</td>
                                                    <td>
                                                        <span class="badge bg-info-subtle text-info border border-info-subtle">
                                                            {{ ucfirst(str_replace('_', ' ', $demande->statut)) }}
                                                        </span>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge bg-light text-dark border">
                                                            {{ $demande->details->count() }}
                                                        </span>
                                                    </td>
                                                    <td class="text-end">
                                                        <button type="button" class="btn btn-sm btn-outline-info text-info" data-bs-toggle="modal" data-bs-target="#demandeModal{{ $demande->id }}">
                                                            <i class="bi bi-eye me-1"></i> Détails
                                                        </button>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center py-5 text-muted">
                                                        <i class="bi bi-eyedropper fs-1 text-muted opacity-50 d-block mb-2"></i>
                                                        Aucune demande d'examen enregistrée.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                @if($demandesExamens->hasPages())
                                    <div class="mt-3 px-3">
                                        {{ $demandesExamens->appends(request()->except('examens_page'))->links() }}
                                    </div>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ==================== MODALS ==================== -->

        <!-- Modals de détails Examens -->
        @foreach($demandesExamens as $demande)
            <div class="modal fade" id="demandeModal{{ $demande->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                        
                        <form action="{{ route('medecin.consultations.examens.update-resultats', [$consultation, $demande]) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <input type="hidden" name="examens_page" value="{{ request('examens_page', 1) }}">

                            <!-- En-tête du Modal -->
                            <div class="modal-header bg-dark text-white py-3 px-4" style="background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="p-2 bg-white bg-opacity-20 rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                                        <i class="bi bi-file-earmark-medical text-white fs-4"></i>
                                    </div>
                                    <div>
                                        <h5 class="modal-title fw-bold text-gray mb-0">Demande d'examens N°{{ $demande->id }} Nombre d'examens: {{ $demande->typesExamens->count() }}</h5>
                                        <div class="d-flex align-items-center gap-2 small text-white-50 mt-1">
                                            <span><i class="bi bi-calendar3 me-1"></i>Demandé le {{ \Carbon\Carbon::parse($demande->date_demande)->format('d/m/Y') }}</span>
                                            <span>&bull;</span>
                                            <span>Statut : <span class="badge bg-white text-primary fw-semibold">{{ ucfirst(str_replace('_', ' ', $demande->statut)) }}</span></span>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>

                            <!-- Corps du Modal -->
                            <div class="modal-body p-4 bg-light bg-opacity-50" style="max-height: 70vh; overflow-y: auto;">
                                
                                <!-- Instructions générales -->
                                @if($demande->instructions)
                                    <div class="mb-4 p-3 bg-white rounded-3 border-start border-4 border-info shadow-2xs">
                                        <div class="d-flex align-items-center gap-2 text-info fw-bold small text-uppercase mb-1">
                                            <i class="bi bi-info-circle-fill"></i> Consignes et instructions générales
                                        </div>
                                        <p class="mb-0 text-secondary small">{{ $demande->instructions }}</p>
                                    </div>
                                @endif

                                <!-- Liste des examens sous forme de Cartes Médicales -->
                                <div class="d-flex flex-column gap-3">
                                    @forelse($demande->details as $index => $detail)
                                        @php
                                            $hasResult = !empty($detail->resultat);
                                        @endphp

                                        <div class="card border-0 shadow-2xs rounded-3 overflow-hidden border-start border-4 {{ $hasResult ? 'border-success' : 'border-primary' }}">
                                            <input type="hidden" name="resultats[{{ $index }}][id]" value="{{ $detail->id }}">

                                            <!-- En-tête de la carte examen -->
                                            <div class="card-header bg-white py-3 px-4 border-bottom d-flex flex-wrap justify-content-between align-items-center gap-2">
                                                <div class="d-flex align-items-center gap-3">
                                                    <div class="badge {{ $hasResult ? 'bg-success text-white' : 'bg-primary text-white' }} rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                                        <i class="bi {{ $hasResult ? 'bi-check-lg' : 'bi-hourglass-split' }}"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="fw-bold text-dark mb-0 fs-6">
                                                            {{ $detail->typeExamen->nom ?? 'Examen' }}
                                                        </h6>
                                                        <span class="badge {{ $hasResult ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-warning-subtle text-warning border border-warning-subtle' }} mt-1" style="font-size: 0.72rem;">
                                                            {{ $hasResult ? 'Résultat enregistré' : 'En attente de résultat' }}
                                                        </span>
                                                    </div>
                                                </div>

                                                <!-- Champ Date de résultat -->
                                                <div class="d-flex align-items-center gap-2 bg-light p-1 px-2 rounded-2 border">
                                                    <label class="small text-muted mb-0 fw-medium">
                                                        <i class="bi bi-calendar-check text-primary me-1"></i>Date :
                                                    </label>
                                                    <input type="date" 
                                                        name="resultats[{{ $index }}][date_resultat]" 
                                                        class="form-control form-control-sm bg-white border-0 py-0 shadow-none" 
                                                        style="width: 135px;"
                                                        value="{{ old('resultats.'.$index.'.date_resultat', $detail->date_resultat ? \Carbon\Carbon::parse($detail->date_resultat)->format('Y-m-d') : '') }}">
                                                </div>
                                            </div>

                                            <!-- Corps de la carte -->
                                            <div class="card-body p-4 bg-white">
                                                
                                                <!-- Indication / Observation préalable -->
                                                @if($detail->observation)
                                                    <div class="p-2 px-3 mb-3 bg-light rounded-2 text-muted small d-flex align-items-center gap-2">
                                                        <i class="bi bi-chat-left-text text-secondary"></i>
                                                        <span><strong>Indication demandée :</strong> {{ $detail->observation }}</span>
                                                    </div>
                                                @endif

                                                <!-- Zone de résultat -->
                                                <div>
                                                    <label class="form-label small fw-bold text-dark mb-2 d-flex align-items-center gap-1">
                                                        <i class="bi bi-pencil-square text-primary"></i> Compte-rendu & Conclusion de l'examen :
                                                    </label>
                                                    <textarea name="resultats[{{ $index }}][resultat]" 
                                                            class="form-control focus-ring" 
                                                            rows="3" 
                                                            style="border-color: #dee2e6; resize: vertical;"
                                                            placeholder="Rédigez le compte-rendu, les valeurs biologiques, les observations ou la conclusion...">{{ old('resultats.'.$index.'.resultat', $detail->resultat) }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center py-5 bg-white rounded-3 border">
                                            <i class="bi bi-inbox fs-1 text-muted opacity-50 d-block mb-2"></i>
                                            <p class="text-muted mb-0">Aucun examen demandé dans ce dossier.</p>
                                        </div>
                                    @endforelse
                                </div>

                            </div>

                            <!-- Pied de page du Modal -->
                            <div class="modal-footer bg-white border-top px-4 py-3 d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="bi bi-shield-check text-success me-1"></i>Modifications enregistrées dans le dossier patient.
                                </small>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-light border px-4" data-bs-dismiss="modal">Fermer</button>
                                    <button type="submit" class="btn btn-primary px-4 fw-semibold d-flex align-items-center gap-2 shadow-sm">
                                        <i class="bi bi-check2-circle"></i> Enregistrer les résultats
                                    </button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        @endforeach

        <!-- Modals de détails Ordonnances -->
        @foreach($ordonnances as $ordonnance)
            <div class="modal fade" id="ordonnanceModal{{ $ordonnance->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content border-0 shadow rounded-4">
                        <div class="modal-header border-bottom py-3 px-4">
                            <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2">
                                <i class="bi bi-prescription2 text-primary"></i> Ordonnance #{{ $ordonnance->id }}
                            </h5>
                            <button class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body p-4">
                            @if($ordonnance->notes)
                                <div class="mb-4 p-3 bg-light rounded-3 border">
                                    <span class="text-muted small fw-semibold d-block mb-1">Instructions :</span>
                                    <p class="mb-0 text-dark small">{{ $ordonnance->notes }}</p>
                                </div>
                            @endif

                            <div class="table-responsive rounded-3 border">
                                <table class="table align-middle mb-0">
                                    <thead class="table-light small text-uppercase">
                                        <tr>
                                            <th>Médicament</th>
                                            <th>Quantité</th>
                                            <th>Posologie</th>
                                            <th>Durée</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($ordonnance->details as $detail)
                                            <tr>
                                                <td class="fw-semibold text-dark">{{ $detail->medicament->nom }}</td>
                                                <td><span class="badge bg-light text-dark border">{{ $detail->quantite }}</span></td>
                                                <td class="text-secondary small">{{ $detail->frequence }}</td>
                                                <td class="text-secondary small">{{ $detail->duree_jours }} jours</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer border-0 px-4 pb-4">
                            <button type="button" class="btn btn-light border px-4" data-bs-dismiss="modal">Fermer</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Modal : Prescrire une ordonnance (AVEC SCROLL INTERNE ET DÉDUPLICATION) -->
        <div class="modal fade" id="prescrireOrdonnanceModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content border-0 shadow rounded-4 overflow-hidden">
                    <form action="{{ route('medecin.consultations.ordonnances.store', $consultation->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="consultation_id" value="{{ $consultation->id }}">

                        <div class="modal-header bg-primary text-white py-3 px-4">
                            <h5 class="modal-title fw-bold text-white d-flex align-items-center gap-2">
                                <i class="bi bi-capsule"></i> Nouvelle Ordonnance
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body p-4">
                            
                            <!-- Conteneur défilant pour la liste des médicaments -->
                            <div class="items-scroll-box border rounded-3 mb-3">
                                <table class="table align-middle mb-0">
                                    <thead class="sticky-top bg-light shadow-2xs small text-uppercase text-muted">
                                        <tr>
                                            <th style="width: 32%;" class="ps-3">Médicament</th>
                                            <th style="width: 13%;">Qté</th>
                                            <th style="width: 30%;">Posologie</th>
                                            <th style="width: 18%;">Durée</th>
                                            <th style="width: 7%;" class="text-center pe-3"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="medicamentsTable">
                                        <tr class="medicament-row">
                                            <td class="ps-3">
                                                <select class="form-select medicament-select" name="medicaments[0][id]" required>
                                                    <option value="" disabled selected>Choisir un médicament...</option>
                                                    @foreach($medicaments as $medicament)
                                                        <option value="{{ $medicament->id }}">{{ $medicament->nom }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control" name="medicaments[0][quantite]" min="1" value="1" placeholder="Qté" required>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="medicaments[0][posologie]" placeholder="Ex: 1 comp. matin et soir" required>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="medicaments[0][duree]" placeholder="Ex: 7 jours" required>
                                            </td>
                                            <td class="text-center pe-3">
                                                <button type="button" class="btn btn-outline-danger btn-sm border-0 removeRow" title="Supprimer">
                                                    <i class="bi bi-trash fs-5"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <button type="button" class="btn btn-sm btn-outline-success rounded-pill px-3" id="addRow">
                                <i class="bi bi-plus-circle me-1"></i> Ajouter un médicament
                            </button>

                            <hr class="my-3">

                            <div>
                                <label class="form-label fw-semibold text-dark small">Instructions générales</label>
                                <textarea class="form-control" rows="2" name="instructions" placeholder="Conseils particuliers, précautions d'emploi..."></textarea>
                            </div>
                        </div>

                        <div class="modal-footer bg-light border-0 px-4 py-3">
                            <button class="btn btn-light border px-4" data-bs-dismiss="modal" type="button">Annuler</button>
                            <button class="btn btn-primary px-4 fw-semibold d-flex align-items-center gap-2" type="submit">
                                <i class="bi bi-check2-circle"></i> Enregistrer l'ordonnance
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal : Demande d'examens (AVEC SCROLL INTERNE ET DÉDUPLICATION) -->
        <div class="modal fade" id="demandeExamenModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content border-0 shadow rounded-4 overflow-hidden">
                    <form action="{{ route('medecin.consultations.examens.store', $consultation->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="consultation_id" value="{{ $consultation->id }}">

                        <div class="modal-header bg-info text-white py-3 px-4">
                            <h5 class="modal-title fw-bold text-white d-flex align-items-center gap-2">
                                <i class="bi bi-eyedropper"></i> Demande d'examens
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body p-4">
                            
                            <!-- Conteneur défilant pour la liste des examens -->
                            <div class="items-scroll-box border rounded-3 mb-3">
                                <table class="table align-middle mb-0">
                                    <thead class="sticky-top bg-light shadow-2xs small text-uppercase text-muted">
                                        <tr>
                                            <th style="width: 45%;" class="ps-3">Examen</th>
                                            <th style="width: 45%;">Observation / Précision</th>
                                            <th style="width: 10%;" class="text-center pe-3"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="examensTable">
                                        <tr class="examen-row">
                                            <td class="ps-3">
                                                <select class="form-select examen-select" name="examens[0][id]" required>
                                                    <option value="" disabled selected>Sélectionner un examen...</option>
                                                    @foreach($typesExamens as $type)
                                                        <option value="{{ $type->id }}">{{ $type->nom }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="examens[0][observation]" placeholder="Ex : À jeun, profil spécifique...">
                                            </td>
                                            <td class="text-center pe-3">
                                                <button type="button" class="btn btn-outline-danger btn-sm border-0 removeExam" title="Supprimer">
                                                    <i class="bi bi-trash fs-5"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <button type="button" class="btn btn-sm btn-outline-info rounded-pill px-3" id="addExam">
                                <i class="bi bi-plus-circle me-1"></i> Ajouter un examen
                            </button>

                            <hr class="my-3">

                            <div>
                                <label class="form-label fw-semibold text-dark small">Instructions générales</label>
                                <textarea class="form-control" rows="2" name="instructions" placeholder="Instructions destinées au laboratoire ou au patient..."></textarea>
                            </div>
                        </div>

                        <div class="modal-footer bg-light border-0 px-4 py-3">
                            <button type="button" class="btn btn-light border px-4" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-info text-white px-4 fw-semibold d-flex align-items-center gap-2">
                                <i class="bi bi-send-check"></i> Enregistrer la demande
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    /* Onglets */
    .nav-tabs-custom .nav-link {
        color: #6c757d;
        border-bottom: 2px solid transparent !important;
        background: transparent;
    }
    .nav-tabs-custom .nav-link.active {
        color: var(--bs-primary) !important;
        border-bottom: 2px solid var(--bs-primary) !important;
        background: transparent;
    }

    /* Boîte défilante pour le tableau des prescriptions/examens */
    .items-scroll-box {
        max-height: 280px;
        overflow-y: auto;
        overflow-x: hidden;
        background: #fff;
    }
    .items-scroll-box .sticky-top {
        z-index: 2;
        background-color: #f8f9fa !important;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }
</style>

<script>
    // Données complètes transmises en JSON
    const allMedicaments = @json($medicaments);
    const allExamens = @json($typesExamens);

    // Initialisation onglets
    document.addEventListener('DOMContentLoaded', function () {
        const params = new URLSearchParams(window.location.search);
        let activeTabId = 'ordonnances-tab';
        let activePaneId = 'ordonnances-pane';

        if (params.has('examens_page')) {
            activeTabId = 'examens-tab';
            activePaneId = 'examens-pane';
        }

        const tabTrigger = document.getElementById(activeTabId);
        const pane = document.getElementById(activePaneId);
        if (tabTrigger && pane) {
            tabTrigger.classList.add('active');
            pane.classList.add('show', 'active');
        }
    });

    /* ==========================================================
       GESTION DES MÉDICAMENTS (Scroll + Pas de doublons)
       ========================================================== */
    let index = 1;

    function updateMedicamentDropdowns() {
        const selects = document.querySelectorAll('.medicament-select');
        const selectedValues = Array.from(selects).map(s => s.value).filter(v => v !== "");

        selects.forEach(select => {
            const currentValue = select.value;
            Array.from(select.options).forEach(opt => {
                if (!opt.value) return; // ignore le placeholder
                if (selectedValues.includes(opt.value) && opt.value !== currentValue) {
                    opt.hidden = true;
                    opt.disabled = true;
                } else {
                    opt.hidden = false;
                    opt.disabled = false;
                }
            });
        });
    }

    // Écouteur sur changement de sélection
    document.getElementById('medicamentsTable').addEventListener('change', function(e) {
        if (e.target.classList.contains('medicament-select')) {
            updateMedicamentDropdowns();
        }
    });

    // Ajout d'une nouvelle ligne médicament
    document.getElementById('addRow').addEventListener('click', function () {
        // Obtenir la liste des IDs déjà sélectionnés
        const currentSelected = Array.from(document.querySelectorAll('.medicament-select'))
            .map(s => s.value)
            .filter(v => v !== "");

        // Générer les options disponibles
        let optionsHtml = `<option value="" disabled selected>Choisir un médicament...</option>`;
        allMedicaments.forEach(med => {
            const isSelected = currentSelected.includes(med.id.toString());
            optionsHtml += `<option value="${med.id}" ${isSelected ? 'hidden disabled' : ''}>${med.nom}</option>`;
        });

        let row = `
        <tr class="medicament-row">
            <td class="ps-3">
                <select class="form-select medicament-select" name="medicaments[${index}][id]" required>
                    ${optionsHtml}
                </select>
            </td>
            <td>
                <input type="number" class="form-control" name="medicaments[${index}][quantite]" min="1" value="1" placeholder="Qté" required>
            </td>
            <td>
                <input type="text" class="form-control" name="medicaments[${index}][posologie]" placeholder="Ex: 1 comp. matin et soir" required>
            </td>
            <td>
                <input type="text" class="form-control" name="medicaments[${index}][duree]" placeholder="Ex: 7 jours" required>
            </td>
            <td class="text-center pe-3">
                <button type="button" class="btn btn-outline-danger btn-sm border-0 removeRow" title="Supprimer">
                    <i class="bi bi-trash fs-5"></i>
                </button>
            </td>
        </tr>
        `;

        document.getElementById('medicamentsTable').insertAdjacentHTML('beforeend', row);
        index++;
        updateMedicamentDropdowns();

        // Faire défiler automatiquement vers le bas de la liste
        const scrollBox = document.querySelector('#prescrireOrdonnanceModal .items-scroll-box');
        scrollBox.scrollTop = scrollBox.scrollHeight;
    });

    // Suppression d'une ligne médicament
    document.addEventListener('click', function(e) {
        if(e.target.closest('.removeRow')) {
            let rows = document.querySelectorAll('#medicamentsTable tr');
            if(rows.length > 1) {
                e.target.closest('tr').remove();
                updateMedicamentDropdowns();
            }
        }
    });


    /* ==========================================================
       GESTION DES EXAMENS (Scroll + Pas de doublons)
       ========================================================== */
    let examIndex = 1;

    function updateExamDropdowns() {
        const selects = document.querySelectorAll('.examen-select');
        const selectedValues = Array.from(selects).map(s => s.value).filter(v => v !== "");

        selects.forEach(select => {
            const currentValue = select.value;
            Array.from(select.options).forEach(opt => {
                if (!opt.value) return; // ignore le placeholder
                if (selectedValues.includes(opt.value) && opt.value !== currentValue) {
                    opt.hidden = true;
                    opt.disabled = true;
                } else {
                    opt.hidden = false;
                    opt.disabled = false;
                }
            });
        });
    }

    // Écouteur sur changement de sélection
    document.getElementById('examensTable').addEventListener('change', function(e) {
        if (e.target.classList.contains('examen-select')) {
            updateExamDropdowns();
        }
    });

    // Ajout d'une nouvelle ligne examen
    document.getElementById('addExam').addEventListener('click', function () {
        const currentSelected = Array.from(document.querySelectorAll('.examen-select'))
            .map(s => s.value)
            .filter(v => v !== "");

        let optionsHtml = `<option value="" disabled selected>Sélectionner un examen...</option>`;
        allExamens.forEach(type => {
            const isSelected = currentSelected.includes(type.id.toString());
            optionsHtml += `<option value="${type.id}" ${isSelected ? 'hidden disabled' : ''}>${type.nom}</option>`;
        });

        let row = `
        <tr class="examen-row">
            <td class="ps-3">
                <select class="form-select examen-select" name="examens[${examIndex}][id]" required>
                    ${optionsHtml}
                </select>
            </td>
            <td>
                <input type="text" class="form-control" name="examens[${examIndex}][observation]" placeholder="Ex : À jeun, profil spécifique...">
            </td>
            <td class="text-center pe-3">
                <button type="button" class="btn btn-outline-danger btn-sm border-0 removeExam" title="Supprimer">
                    <i class="bi bi-trash fs-5"></i>
                </button>
            </td>
        </tr>
        `;

        document.getElementById('examensTable').insertAdjacentHTML('beforeend', row);
        examIndex++;
        updateExamDropdowns();

        // Faire défiler automatiquement vers le bas de la liste
        const scrollBox = document.querySelector('#demandeExamenModal .items-scroll-box');
        scrollBox.scrollTop = scrollBox.scrollHeight;
    });

    // Suppression d'une ligne examen
    document.addEventListener('click', function(e) {
        if(e.target.closest('.removeExam')) {
            let rows = document.querySelectorAll('#examensTable tr');
            if(rows.length > 1) {
                e.target.closest('tr').remove();
                updateExamDropdowns();
            }
        }
    });
</script>
@endsection