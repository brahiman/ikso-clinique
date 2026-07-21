@extends('layouts.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4>Dossier Médical - {{ $patient->prenom }} {{ $patient->nom }}</h4>
                <a href="{{ route('patient.dossier.index') }}" class="btn btn-secondary">Retour à la liste</a>
            </div>

            <div class="row">

                <!-- 1. Informations Générales -->
                <div class="col-lg-12 mb-4">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5>Informations Générales</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <p><strong>Date de naissance :</strong> {{ $patient->date_naissance?->format('d/m/Y') }}</p>
                                </div>
                                <div class="col-md-3">
                                    <p><strong>Groupe sanguin :</strong> {{ $patient->groupe_sanguin }}</p>
                                </div>
                                <div class="col-md-3">
                                    <p><strong>Téléphone :</strong> {{ $patient->telephone }}</p>
                                </div>
                                <div class="col-md-3">
                                    <p><strong>Adresse :</strong> {{ $patient->adresse ?? 'Non renseignée' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Antécédents Médicaux -->
                <div class="col-lg-12">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5>Antécédents Médicaux</h5>
                        </div>
                        <div class="card-body">
                            @if($patient->antecedents->isEmpty())
                                <p class="text-muted">Aucun antécédent déclaré.</p>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th>Type</th>
                                            <th>Nom</th>
                                            <th>Description</th>
                                            <th>Date</th>
                                            <th>Gravité</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($patient->antecedents as $antecedent)
                                            <tr>
                                                <td><span class="badge bg-secondary">{{ $antecedent->type }}</span></td>
                                                <td><strong>{{ $antecedent->nom }}</strong></td>
                                                <td>{{ $antecedent->description ?? '—' }}</td>
                                                <td>{{ $antecedent->date_evenement?->format('d/m/Y') ?? '—' }}</td>
                                                <td>
                                                    @if($antecedent->gravite)
                                                        <span class="badge bg-{{ $antecedent->gravite == 'haute' ? 'danger' : 'warning' }}">{{ $antecedent->gravite }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- 3. Examens Complémentaires -->
                <div class="col-lg-12">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5>Examens Complémentaires</h5>
                        </div>
                        <div class="card-body">
                            @if($patient->demandesExamens->isEmpty())
                                <p class="text-muted">Aucun examen enregistré.</p>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th>Type</th>
                                            <th>Description</th>
                                            <th>Date Demande</th>
                                            <th>Résultat</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($patient->demandesExamens as $examen)
                                            <tr>
                                                <td>{{ $examen->type_examen }}</td>
                                                <td>{{ $examen->description }}</td>
                                                <td>{{ $examen->date_demande->format('d/m/Y') }}</td>
                                                <td>
                                                    @if($examen->resultats)
                                                        <span class="text-success">{{ $examen->resultats }}</span>
                                                    @else
                                                        <span class="badge bg-warning">En attente</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- 4. Traitements & Ordonnances -->

                <div class="col-lg-12 mt-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>Traitements & Ordonnances</h5>
                        </div>
                        <div class="card-body">
                            @if($patient->ordonnances->isEmpty())
                                <p class="text-muted">Aucune ordonnance enregistrée.</p>
                            @else
                                @foreach($patient->ordonnances as $ord)
                                    <div class="card mb-3">
                                        <div class="card-header bg-light">
                                            <strong>{{ $ord->date_prescription->format('d/m/Y') }}</strong> —
                                            Dr. {{ $ord->medecin->user->name }}
                                        </div>
                                        <div class="card-body">
                                            <div class="row">


                                            <!-- Détails des médicaments (si tu utilises la table pivot) -->
                                            @if($ord->details->isNotEmpty())
                                                <table class="table table-sm">
                                                    <thead>
                                                    <tr>
                                                        <th>Médicament</th>
                                                        <th>Quantité</th>
                                                        <th>Fréquence</th>
                                                        <th>Durée</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($ord->details as $detail)
                                                        <tr>
                                                            <td>{{ $detail->medicament->nom ?? '—' }}</td>
                                                            <td>{{ $detail->quantite }}</td>
                                                            <td>{{ $detail->frequence }}</td>
                                                            <td>{{ $detail->duree_jours }} jours</td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

                <!-- 5. Historique des Consultations -->
                <div class="col-lg-12 mt-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>Historique des Consultations</h5>
                        </div>
                        <div class="card-body">
                            @if($patient->consultations->isEmpty())
                                <p class="text-muted">Aucune consultation enregistrée.</p>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Médecin</th>
                                            <th>Diagnostic</th>
                                            <th>Observations</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($patient->consultations as $consult)
                                            <tr>
                                                <td>{{ $consult->date_consultation->format('d/m/Y') }}</td>
                                                <td>Dr. {{ $consult->medecin->user->name }}</td>
                                                <td>{{ $consult->diagnostic ?? '—' }}</td>
                                                <td>{{ Str::limit($consult->observations, 80) }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
