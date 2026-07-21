@extends('layouts.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="d-flex justify-content-between mb-4">
                <h4>Détails du Patient</h4>
                <a href="{{ route('secretaire.patients.index') }}" class="btn btn-secondary">Retour à la liste</a>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Informations Personnelles</h5>
                            <p><strong>Nom :</strong> {{ $patient->prenom }} {{ $patient->nom }}</p>
                            <p><strong>Date de naissance :</strong> {{ $patient->date_naissance?->format('d/m/Y') }}</p>
                            <p><strong>Sexe :</strong> {{ $patient->sexe }}</p>
                            <p><strong>Téléphone :</strong> {{ $patient->telephone }}</p>
                            <p><strong>Email :</strong> {{ $patient->email ?? 'Non renseigné' }}</p>
                        </div>
                        <div class="col-md-6">
                            <h5>Informations Médicales</h5>
                            <p><strong>Groupe sanguin :</strong> {{ $patient->groupe_sanguin }}</p>
                            <p><strong>Adresse :</strong> {{ $patient->adresse ?? 'Non renseignée' }}</p>
                            <p><strong>Contact d'urgence :</strong> {{ $patient->contact_urgence_nom ?? 'Non renseigné' }} ({{ $patient->contact_urgence_telephone ?? '-' }})</p>

                            <p class="mt-3"> <strong>Médecin assigné :</strong>
                            @if($patient->medecins->count() > 0)
                                @foreach($patient->medecins as $med)
                                    <span class="badge bg-success">Dr. {{ $med->user->name }}</span><br>
                                @endforeach
                            @else
                                <span class="text-muted small">Aucun médecin assigné</span>
                            @endif
                            </p>
                        </div>
                    </div>

                    <div class="mt-4 d-flex gap-2">
                        <a href="{{ route('secretaire.patients.edit', $patient) }}" class="btn btn-warning">
                            Modifier les informations
                        </a>
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#affecterModal">
                            <i class="ri-user-add-line"></i> Affecter à un médecin
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal Affectation -->
    <div class="modal fade" id="affecterModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Affecter {{ $patient->prenom }} {{ $patient->nom }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('secretaire.patients.affecter', $patient) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Choisir un médecin</label>
                            <select name="medecin_id" class="form-control" required>
                                <option value="">-- Sélectionner --</option>
                                @foreach(\App\Models\Medecin::with('user')->get() as $med)
                                    <option value="{{ $med->id }}">Dr. {{ $med->user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-success">Affecter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
