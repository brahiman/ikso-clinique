@extends('layouts.master')

@section('content')
<div class="page-content">
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>
                Dossier du patient :
                {{ $patient->prenom }} {{ $patient->nom }}
            </h2>

            <a href="{{ route('medecin.patients.index') }}"
               class="btn btn-secondary">
                Retour
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Informations patient (pleine largeur) --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Informations générales</h5>
            </div>

            <div class="card-body">
                <div class="row">

                    <div class="col-md-3 mb-3 mb-md-0">
                        <strong>Nom :</strong><br>
                        {{ $patient->nom }}
                    </div>

                    <div class="col-md-3 mb-3 mb-md-0">
                        <strong>Prénom :</strong><br>
                        {{ $patient->prenom }}
                    </div>

                    <div class="col-md-2 mb-3 mb-md-0">
                        <strong>Sexe :</strong><br>
                        {{ $patient->sexe }}
                    </div>

                    <div class="col-md-2 mb-3 mb-md-0">
                        <strong>Date de naissance :</strong><br>
                        {{ optional($patient->date_naissance)->format('d/m/Y') ?? '-' }}
                    </div>

                    <div class="col-md-2 mb-3 mb-md-0">
                        <strong>Groupe sanguin :</strong><br>
                        {{ $patient->groupe_sanguin }}
                    </div>

                    <div class="col-md-3 mt-3">
                        <strong>Téléphone :</strong><br>
                        {{ $patient->telephone }}
                    </div>

                    <div class="col-md-3 mt-3">
                        <strong>Email :</strong><br>
                        {{ $patient->email ?? '-' }}
                    </div>

                    <div class="col-md-6 mt-3">
                        <strong>Adresse :</strong><br>
                        {{ $patient->adresse ?? '-' }}
                    </div>

                </div>
            </div>
        </div>

        {{-- Dossier médical / Historique (pleine largeur, à la ligne) --}}
        <div class="row">
            <div class="col-12">

                {{-- Onglets --}}
                <ul class="nav nav-tabs" id="patientTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="dossier-tab" data-bs-toggle="tab"
                                data-bs-target="#dossier-pane" type="button" role="tab">
                            <i class="ri-folder-2-line"></i> Dossier médical
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="historique-tab" data-bs-toggle="tab"
                                data-bs-target="#historique-pane" type="button" role="tab">
                            <i class="ri-history-line"></i> Historique
                        </button>
                    </li>
                </ul>

                <div class="tab-content border border-top-0 p-3 mb-3" id="patientTabsContent">

                    {{-- ===== Onglet Dossier médical ===== --}}
                    <div class="tab-pane fade show active" id="dossier-pane" role="tabpanel">

                        {{-- Notes générales --}}
                        <div class="card mb-4">

                            <div class="card-header">
                                <h5 class="mb-0">Notes générales</h5>
                            </div>

                            <div class="card-body">

                                <form method="POST"
                                      action="{{ route('medecin.patients.dossierMedical.update', $patient) }}">

                                    @csrf
                                    @method('PUT')

                                    <div class="mb-3">

                                        <textarea
                                            class="form-control"
                                            rows="5"
                                            name="notes_generales"
                                            placeholder="Notes générales sur le patient...">{{ old('notes_generales', $patient->dossierMedical->notes_generales) }}</textarea>

                                    </div>

                                    <button class="btn btn-primary">
                                        Enregistrer
                                    </button>

                                </form>

                            </div>

                        </div>

                        {{-- Antécédents médicaux --}}
                        <div class="card">

                            <div class="card-header d-flex justify-content-between align-items-center">

                                <h5 class="mb-0">
                                    Antécédents médicaux
                                </h5>

                                <button
                                    class="btn btn-success btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#antecedentModal"
                                    onclick="ouvrirModalAjout()">
                                    <i class="ri-add-line"></i> Ajouter
                                </button>

                            </div>

                            <div class="card-body">

                                <div class="table-responsive">

                                    <table class="table table-bordered table-hover align-middle">

                                        <thead>
                                            <tr>
                                                <th>Type</th>
                                                <th>Nom</th>
                                                <th>Date</th>
                                                <th>Gravité</th>
                                                <th>Statut</th>
                                                <th width="110">Actions</th>
                                            </tr>
                                        </thead>

                                        <tbody>

                                        @forelse($patient->dossierMedical->antecedentsMedicaux as $antecedent)

                                            <tr>

                                                <td>{{ ucfirst($antecedent->type) }}</td>

                                                <td>{{ $antecedent->nom }}</td>

                                                <td>
                                                    {{ optional($antecedent->date_evenement)->format('d/m/Y') ?? '-' }}
                                                </td>

                                                <td>
                                                    {{ ucfirst($antecedent->gravite ?? '-') }}
                                                </td>

                                                <td>
                                                    @if($antecedent->actif)
                                                        <span class="badge bg-success">Actif</span>
                                                    @else
                                                        <span class="badge bg-secondary">Inactif</span>
                                                    @endif
                                                </td>

                                                <td>

                                                    <button
                                                        type="button"
                                                        class="btn btn-warning btn-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#antecedentModal"
                                                        onclick="ouvrirModalEdition({{ Js::from([
                                                            'id' => $antecedent->id,
                                                            'type' => $antecedent->type,
                                                            'nom' => $antecedent->nom,
                                                            'description' => $antecedent->description,
                                                            'date_evenement' => optional($antecedent->date_evenement)->format('Y-m-d'),
                                                            'gravite' => $antecedent->gravite,
                                                            'actif' => $antecedent->actif,
                                                        ]) }})">
                                                        <i class="ri-edit-line"></i>
                                                    </button>

                                                    <button
                                                        type="button"
                                                        class="btn btn-danger btn-sm"
                                                        onclick="if(confirm('Supprimer cet antécédent ?')){ document.getElementById('deleteForm{{ $antecedent->id }}').submit(); }">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>

                                                    <form id="deleteForm{{ $antecedent->id }}"
                                                          method="POST"
                                                          action="{{ route('medecin.patients.antecedents.destroy', [$patient, $antecedent]) }}"
                                                          class="d-none">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>

                                                </td>

                                            </tr>

                                        @empty

                                            <tr>
                                                <td colspan="6" class="text-center">
                                                    Aucun antécédent enregistré.
                                                </td>
                                            </tr>

                                        @endforelse

                                        </tbody>

                                    </table>

                                </div>

                            </div>

                        </div>

                    </div>

                    {{-- ===== Onglet Historique ===== --}}
                    <div class="tab-pane fade" id="historique-pane" role="tabpanel">

                        {{-- Consultations --}}
                        <div class="card mb-4">

                            <div class="card-header">
                                <h6 class="mb-0">Consultations</h6>
                            </div>

                            <div class="card-body">

                                <table class="table table-bordered table-sm">

                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Diagnostic</th>
                                            <th>Statut</th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                    @forelse($patient->consultations as $consultation)

                                        <tr>
                                            <td>{{ $consultation->date_consultation->format('d/m/Y') }}</td>
                                            <td>{{ $consultation->diagnostic ?? '-' }}</td>
                                            <td>{{ ucfirst($consultation->statut) }}</td>
                                        </tr>

                                    @empty

                                        <tr>
                                            <td colspan="3" class="text-center">Aucune consultation.</td>
                                        </tr>

                                    @endforelse

                                    </tbody>

                                </table>

                            </div>

                        </div>

                        {{-- Ordonnances --}}
                        <div class="card mb-4">

                            <div class="card-header">
                                <h6 class="mb-0">Ordonnances</h6>
                            </div>

                            <div class="card-body">

                                <table class="table table-bordered table-sm">

                                    <thead>
                                        <tr>
                                            <th>N°</th>
                                            <th>Date</th>
                                            <th>Médecin</th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                    @forelse($patient->ordonnances as $ordonnance)

                                        <tr>
                                            <td>#{{ $ordonnance->id }}</td>
                                            <td>{{ $ordonnance->date_prescription->format('d/m/Y') }}</td>
                                            <td>{{ $ordonnance->medecin->user->name }}</td>
                                        </tr>

                                    @empty

                                        <tr>
                                            <td colspan="3" class="text-center">Aucune ordonnance.</td>
                                        </tr>

                                    @endforelse

                                    </tbody>

                                </table>

                            </div>

                        </div>

                        {{-- Examens --}}
                        <div class="card">

                            <div class="card-header">
                                <h6 class="mb-0">Demandes d'examens</h6>
                            </div>

                            <div class="card-body">

                                <table class="table table-bordered table-sm">

                                    <thead>
                                        <tr>
                                            <th>N°</th>
                                            <th>Date</th>
                                            <th>Statut</th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                    @forelse($patient->demandesExamens as $demande)

                                        <tr>
                                            <td>#{{ $demande->id }}</td>
                                            <td>{{ \Carbon\Carbon::parse($demande->date_demande)->format('d/m/Y') }}</td>
                                            <td>{{ ucfirst(str_replace('_', ' ', $demande->statut)) }}</td>
                                        </tr>

                                    @empty

                                        <tr>
                                            <td colspan="3" class="text-center">Aucune demande d'examen.</td>
                                        </tr>

                                    @endforelse

                                    </tbody>

                                </table>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>
</div>

{{-- ===== Modal unique : Ajouter / Modifier un antécédent ===== --}}
<div class="modal fade"
     id="antecedentModal"
     tabindex="-1">

    <div class="modal-dialog modal-lg">

        <form method="POST"
              id="antecedentForm"
              action="{{ route('medecin.patients.antecedents.store', $patient) }}">

            @csrf
            <input type="hidden" name="_method" id="antecedentMethod" value="POST">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title" id="antecedentModalLabel">
                        Ajouter un antécédent
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label>Type</label>

                            <select
                                name="type"
                                id="antecedentType"
                                class="form-select"
                                required>

                                <option value="maladie">Maladie</option>
                                <option value="allergie">Allergie</option>
                                <option value="operation">Opération</option>
                                <option value="vaccin">Vaccin</option>
                                <option value="familial">Familial</option>
                                <option value="autre">Autre</option>

                            </select>

                        </div>

                        <div class="col-md-6 mb-3">

                            <label>Nom</label>

                            <input
                                type="text"
                                name="nom"
                                id="antecedentNom"
                                class="form-control"
                                required>

                        </div>

                    </div>

                    <div class="mb-3">

                        <label>Description</label>

                        <textarea
                            class="form-control"
                            rows="3"
                            name="description"
                            id="antecedentDescription"></textarea>

                    </div>

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label>Date</label>

                            <input
                                type="date"
                                name="date_evenement"
                                id="antecedentDate"
                                class="form-control">

                        </div>

                        <div class="col-md-6 mb-3">

                            <label>Gravité</label>

                            <select
                                name="gravite"
                                id="antecedentGravite"
                                class="form-select">

                                <option value="">Aucune</option>
                                <option value="faible">Faible</option>
                                <option value="moyenne">Moyenne</option>
                                <option value="haute">Haute</option>

                            </select>

                        </div>

                    </div>

                    <div class="form-check">

                        <input
                            checked
                            class="form-check-input"
                            type="checkbox"
                            value="1"
                            name="actif"
                            id="antecedentActif">

                        <label class="form-check-label">
                            Antécédent actif
                        </label>

                    </div>

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                        Annuler
                    </button>

                    <button type="submit" class="btn btn-primary">
                        Enregistrer
                    </button>

                </div>

            </div>

        </form>

    </div>

</div>

<script>
    const storeUrlTemplate = @json(route('medecin.patients.antecedents.store', $patient));
    const updateUrlTemplate = @json(route('medecin.patients.antecedents.update', [$patient, '__ID__']));

    function ouvrirModalAjout() {
        document.getElementById('antecedentModalLabel').innerText = 'Ajouter un antécédent';
        document.getElementById('antecedentForm').action = storeUrlTemplate;
        document.getElementById('antecedentMethod').value = 'POST';

        document.getElementById('antecedentType').value = 'maladie';
        document.getElementById('antecedentNom').value = '';
        document.getElementById('antecedentDescription').value = '';
        document.getElementById('antecedentDate').value = '';
        document.getElementById('antecedentGravite').value = '';
        document.getElementById('antecedentActif').checked = true;
    }

    function ouvrirModalEdition(antecedent) {
        document.getElementById('antecedentModalLabel').innerText = 'Modifier un antécédent';
        document.getElementById('antecedentForm').action = updateUrlTemplate.replace('__ID__', antecedent.id);
        document.getElementById('antecedentMethod').value = 'PUT';

        document.getElementById('antecedentType').value = antecedent.type;
        document.getElementById('antecedentNom').value = antecedent.nom;
        document.getElementById('antecedentDescription').value = antecedent.description ?? '';
        document.getElementById('antecedentDate').value = antecedent.date_evenement ?? '';
        document.getElementById('antecedentGravite').value = antecedent.gravite ?? '';
        document.getElementById('antecedentActif').checked = !!antecedent.actif;
    }
</script>

@endsection
