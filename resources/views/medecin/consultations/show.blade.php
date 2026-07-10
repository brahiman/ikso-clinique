@extends('layouts.master')

@section('content')
<div class="page-content">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Consultation — {{ $consultation->patient->prenom }} {{ $consultation->patient->nom }}</h2>
            <div>
                <span class="badge bg-{{ $consultation->isTerminee() ? 'success' : 'warning' }}">
                    {{ $consultation->statut }}
                </span>
                @if($consultation->isUrgence())
                    <span class="badge bg-danger">Urgence</span>
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4">
                <div class="card mb-3">
                    <div class="card-body">
                        <h5>Patient</h5>
                        <p class="mb-1"><strong>Nom :</strong> {{ $consultation->patient->prenom }} {{ $consultation->patient->nom }}</p>
                        <p class="mb-1"><strong>Téléphone :</strong> {{ $consultation->patient->telephone }}</p>
                        <p class="mb-1"><strong>Groupe sanguin :</strong> {{ $consultation->patient->groupe_sanguin }}</p>
                        @if($consultation->patient->date_naissance)
                            <p class="mb-0"><strong>Date de naissance :</strong> {{ $consultation->patient->date_naissance->format('d/m/Y') }}</p>
                        @endif
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h5>Origine</h5>
                        @if($consultation->isDirecte())
                            <p class="mb-1"><strong>Type :</strong> Accueil direct</p>
                            <p class="mb-0"><strong>Motif :</strong> {{ $consultation->motif_direct ?? '—' }}</p>
                            @if($consultation->notes_accueil)
                                <p class="mb-0 mt-2"><strong>Notes accueil :</strong> {{ $consultation->notes_accueil }}</p>
                            @endif
                        @else
                            <p class="mb-1"><strong>Type :</strong> Rendez-vous planifié</p>
                            <p class="mb-1"><strong>Date du RDV :</strong> {{ $consultation->rendezVous->date_heure->format('d/m/Y H:i') }}</p>
                            <p class="mb-0"><strong>Motif :</strong> {{ $consultation->rendezVous->motif ?? '—' }}</p>
                        @endif
                        <p class="mb-0 mt-2 text-muted small">Consultation du {{ $consultation->date_consultation->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <h5>Diagnostic</h5>
                        <p class="{{ !$consultation->diagnostic ? 'text-muted' : '' }}">
                            {{ $consultation->diagnostic ?? 'Non renseigné' }}
                        </p>

                        <h5 class="mt-4">Observations</h5>
                        <p class="{{ !$consultation->observations ? 'text-muted' : '' }}">
                            {{ $consultation->observations ?? 'Non renseigné' }}
                        </p>

                        <h5 class="mt-4">Traitement</h5>
                        <p class="{{ !$consultation->traitement ? 'text-muted' : '' }}">
                            {{ $consultation->traitement ?? 'Non renseigné' }}
                        </p>

                        <h5 class="mt-4">Recommandations</h5>
                        <p class="mb-0 {{ !$consultation->recommandations ? 'text-muted' : '' }}">
                            {{ $consultation->recommandations ?? 'Non renseigné' }}
                        </p>
                    </div>
                </div>

                @if(!$consultation->isTerminee())
                    <div class="mt-3">
                        <a href="{{ route('medecin.consultations.edit', $consultation) }}" class="btn btn-primary">
                            Reprendre la consultation
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <div class="mt-3">
            <a href="{{ route('medecin.consultations.index') }}" class="btn btn-outline-secondary">
                ← Retour à mes consultations
            </a>
            {{-- bouton oubre modal pour prescrire une ordonnance --}}
            <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#prescrireOrdonnanceModal">
                Prescrire une ordonnance
            </button>
            <button type="button"
        class="btn btn-info"
        data-bs-toggle="modal"
        data-bs-target="#demandeExamenModal">
    <i class="fas fa-vials"></i>
    Demande d'examens
</button>
            {{-- modal pour ordonance --}}
            <div class="modal fade" id="prescrireOrdonnanceModal" tabindex="-1" aria-labelledby="prescrireOrdonnanceModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">

                        <form action="" method="POST">
                            @csrf

                            <input type="hidden" name="consultation_id" value="{{ $consultation->id }}">

                            <div class="modal-header">
                                <h5 class="modal-title" id="prescrireOrdonnanceModalLabel">
                                    Nouvelle ordonnance
                                </h5>

                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">

                                <div class="table-responsive">

                                    <table class="table table-bordered align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width:25%">Médicament</th>
                                                <th style="width:10%">Qté</th>
                                                <th style="width:30%">Posologie</th>
                                                <th style="width:20%">Durée</th>
                                                <th style="width:15%">Action</th>
                                            </tr>
                                        </thead>

                                        <tbody id="medicamentsTable">

                                            <tr>

                                                <td>
                                                    <select class="form-select"
                                                            name="medicaments[0][id]">

                                                        @foreach($medicaments as $medicament)
                                                            <option value="{{ $medicament->id }}">
                                                                {{ $medicament->nom }}
                                                            </option>
                                                        @endforeach

                                                    </select>
                                                </td>

                                                <td>
                                                    <input type="number"
                                                        class="form-control"
                                                        name="medicaments[0][quantite]"
                                                        min="1">
                                                </td>

                                                <td>
                                                    <input type="text"
                                                        class="form-control"
                                                        name="medicaments[0][posologie]"
                                                        placeholder="Ex : 1 comprimé matin et soir">
                                                </td>

                                                <td>
                                                    <input type="text"
                                                        class="form-control"
                                                        name="medicaments[0][duree]"
                                                        placeholder="Ex : 7 jours">
                                                </td>

                                                <td class="text-center">
                                                    <button type="button"
                                                            class="btn btn-danger btn-sm removeRow">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>

                                            </tr>

                                        </tbody>

                                    </table>

                                </div>

                                <button type="button"
                                        class="btn btn-success"
                                        id="addRow">

                                    <i class="fas fa-plus"></i>
                                    Ajouter un médicament

                                </button>

                                <hr>

                                <div class="mb-3">
                                    <label class="form-label">Instructions générales</label>

                                    <textarea class="form-control"
                                            rows="3"
                                            name="instructions"></textarea>
                                </div>

                            </div>

                            <div class="modal-footer">

                                <button class="btn btn-secondary"
                                        data-bs-dismiss="modal"
                                        type="button">
                                    Annuler
                                </button>

                                <button class="btn btn-primary"
                                        type="submit">
                                    Enregistrer l'ordonnance
                                </button>

                            </div>

                        </form>

                    </div>
                </div>
            </div> 

            {{-- modal pour examens --}}
            <div class="modal fade" id="demandeExamenModal" tabindex="-1" aria-labelledby="demandeExamenModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">

                        <form action="" method="POST">
                            @csrf

                            <input type="hidden" name="consultation_id" value="{{ $consultation->id }}">

                            <div class="modal-header">
                                <h5 class="modal-title" id="demandeExamenModalLabel">
                                    Demande d'examens
                                </h5>

                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">

                                <div class="table-responsive">

                                    <table class="table table-bordered align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th width="35%">Examen</th>
                                                <th width="50%">Observation / Précision</th>
                                                <th width="15%">Action</th>
                                            </tr>
                                        </thead>

                                        <tbody id="examensTable">

                                            <tr>

                                                <td>
                                                    <select class="form-select"
                                                            name="examens[0][id]">

                                                        @foreach($typesExamens as $type)
                                                            <option value="{{ $type->id }}">
                                                                {{ $type->nom }}
                                                            </option>
                                                        @endforeach

                                                    </select>
                                                </td>

                                                <td>
                                                    <input type="text"
                                                        class="form-control"
                                                        name="examens[0][observation]"
                                                        placeholder="Ex : À jeun, Face + Profil...">
                                                </td>

                                                <td class="text-center">
                                                    <button type="button"
                                                            class="btn btn-danger btn-sm removeExam">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>

                                            </tr>

                                        </tbody>

                                    </table>

                                </div>

                                <button type="button"
                                        class="btn btn-success"
                                        id="addExam">

                                    <i class="fas fa-plus"></i>
                                    Ajouter un examen

                                </button>

                                <hr>

                                <div class="mb-3">
                                    <label class="form-label">
                                        Instructions générales
                                    </label>

                                    <textarea class="form-control"
                                            rows="3"
                                            name="instructions"
                                            placeholder="Instructions destinées au laboratoire ou au patient..."></textarea>
                                </div>

                            </div>

                            <div class="modal-footer">

                                <button type="button"
                                        class="btn btn-secondary"
                                        data-bs-dismiss="modal">
                                    Annuler
                                </button>

                                <button type="submit"
                                        class="btn btn-primary">
                                    Enregistrer la demande
                                </button>

                            </div>

                        </form>

                    </div>
                </div>
            </div>

        </div>
    </div>

<script>
        let index = 1;

        document.getElementById('addRow').addEventListener('click', function () {

            let row = `
            <tr>

                <td>
                    <select class="form-select" name="medicaments[${index}][id]">

                        @foreach($medicaments as $medicament)
                            <option value="{{ $medicament->id }}">
                                {{ $medicament->nom }}
                            </option>
                        @endforeach

                    </select>
                </td>

                <td>
                    <input type="number"
                        class="form-control"
                        name="medicaments[${index}][quantite]"
                        min="1">
                </td>

                <td>
                    <input type="text"
                        class="form-control"
                        name="medicaments[${index}][posologie]">
                </td>

                <td>
                    <input type="text"
                        class="form-control"
                        name="medicaments[${index}][duree]">
                </td>

                <td class="text-center">
                    <button type="button"
                            class="btn btn-danger btn-sm removeRow">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>

            </tr>
            `;

            document
                .getElementById('medicamentsTable')
                .insertAdjacentHTML('beforeend', row);

            index++;
        });

        document.addEventListener('click', function(e){

            if(e.target.closest('.removeRow')){

                let rows = document.querySelectorAll('#medicamentsTable tr');

                if(rows.length > 1){
                    e.target.closest('tr').remove();
                }

            }

        });
</script>
<script>

    let examIndex = 1;

    document.getElementById('addExam').addEventListener('click', function () {

        let row = `
            <tr>

                <td>
                    <select class="form-select"
                            name="examens[${examIndex}][id]">

                        @foreach($typesExamens as $type)
                            <option value="{{ $type->id }}">
                                {{ $type->nom }}
                            </option>
                        @endforeach

                    </select>
                </td>

                <td>
                    <input type="text"
                        class="form-control"
                        name="examens[${examIndex}][observation]"
                        placeholder="Observation">
                </td>

                <td class="text-center">
                    <button type="button"
                            class="btn btn-danger btn-sm removeExam">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>

            </tr>
        `;

        document
            .getElementById('examensTable')
            .insertAdjacentHTML('beforeend', row);

        examIndex++;

    });

    document.addEventListener('click', function(e){

        if(e.target.closest('.removeExam')){

            let rows = document.querySelectorAll('#examensTable tr');

            if(rows.length > 1){
                e.target.closest('tr').remove();
            }

        }

    });

</script>
</div>
@endsection