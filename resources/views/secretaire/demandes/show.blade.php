@extends('layouts.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4>Détails de la Demande #{{ $demande->id }}</h4>
                <a href="{{ route('secretaire.demandes.index') }}" class="btn btn-secondary">Retour à la liste</a>
            </div>

            <div class="row">
                <!-- Informations Patient -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5>Informations du Patient</h5>
                        </div>
                        <div class="card-body">
                            <p><strong>Nom complet :</strong> {{ $demande->patient->prenom }} {{ $demande->patient->nom }}</p>
                            <p><strong>Téléphone :</strong> {{ $demande->patient->telephone }}</p>
                            <p><strong>Email :</strong> {{ $demande->patient->email ?? 'Non renseigné' }}</p>
                            <p><strong>Date de naissance :</strong> {{ $demande->patient->date_naissance?->format('d/m/Y') }}</p>
                            <p><strong>Adresse :</strong> {{ $demande->patient->adresse ?? 'Non renseignée' }}</p>
                            <p><strong>Groupe sanguin :</strong> {{ $demande->patient->groupe_sanguin }}</p>
                            <p><strong>Contact d'urgence :</strong> {{ $demande->patient->contact_urgence_nom ?? 'Non renseigné' }}
                                ({{ $demande->patient->contact_urgence_telephone ?? '-' }})</p>
                        </div>
                    </div>
                </div>

                <!-- Informations de la Demande -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <h5>Détails de la Demande</h5>
                        </div>
                        <div class="card-body">
                            <p><strong>Service souhaité :</strong> {{ $demande->service_souhaite }}</p>
                            <p><strong>Motif :</strong> {{ $demande->motif }}</p>
                            <p><strong>Symptômes :</strong> {{ $demande->symptomes }}</p>
                            <p><strong>Niveau d'urgence :</strong>
                                <span class="badge bg-{{ $demande->urgence == 'haute' ? 'danger' : ($demande->urgence == 'moyenne' ? 'warning' : 'info') }}">
                                {{ ucfirst($demande->urgence) }}
                            </span>
                            </p>
                            <p><strong>Disponibilités :</strong> {{ $demande->disponibilite_patient }}</p>
                            <p><strong>Préférence médecin :</strong> {{ $demande->pref_medecin ?? 'Aucune préférence' }}</p>
                            <p><strong>Statut :</strong>
                                <span class="badge bg-{{ $demande->statut == 'en_attente' ? 'warning' : 'success' }}">
                                {{ ucfirst(str_replace('_', ' ', $demande->statut)) }}
                            </span>
                            </p>

                            @if($demande->medecin)
                                <p><strong>Médecin assigné :</strong> Dr. {{ $demande->medecin->user->name }}</p>
                            @endif

                            <p><strong>Date de la demande :</strong> {{ $demande->created_at->format('d/m/Y à H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Affectation si en attente -->
            @if($demande->statut == 'en_attente')
                <div class="card mt-4">
                    <div class="card-header bg-warning">
                        <h5>Affecter cette demande à un médecin</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('secretaire.demandes.affecter', $demande) }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-8">
                                    <select name="medecin_id" class="form-control" required>
                                        <option value="">Choisir un médecin</option>
                                        @foreach(\App\Models\Medecin::with('user')->get() as $med)
                                            <option value="{{ $med->id }}">Dr. {{ $med->user->name }} - {{ $med->specialite?->nom ?? 'Généraliste' }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-success w-100">Affecter</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
