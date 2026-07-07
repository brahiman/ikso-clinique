@extends('layouts.master')

@section('content')
<div class="page-content">
    <div class="container-fluid">
        <h2 class="mb-4">Demande de {{ $demande->patient->prenom }} {{ $demande->patient->nom }}</h2>

        <div class="row">
            <div class="col-lg-7">
                <div class="card mb-3">
                    <div class="card-body">
                        <h5>Détails de la demande</h5>
                        <p><strong>Service souhaité :</strong> {{ $demande->service_souhaite }}</p>
                        <p><strong>Symptômes :</strong> {{ $demande->symptomes }}</p>
                        <p><strong>Urgence :</strong>
                            <span class="badge bg-{{ $demande->urgence === 'haute' ? 'danger' : ($demande->urgence === 'moyenne' ? 'warning' : 'secondary') }}">
                                {{ $demande->urgence }}
                            </span>
                        </p>
                        <p><strong>Disponibilité indiquée par le patient :</strong> {{ $demande->disponibilite_patient ?? '—' }}</p>
                        <p class="mb-0"><strong>Téléphone patient :</strong> {{ $demande->patient->telephone }}</p>
                    </div>
                </div>

                @if($demande->medecin && $demande->medecin->disponibilite)
                    <div class="card">
                        <div class="card-body">
                            <h5>Mes disponibilités habituelles</h5>
                            <ul class="list-unstyled mb-0">
                                @foreach($demande->medecin->disponibilite as $jour => $creneaux)
                                    <li><strong>{{ ucfirst($jour) }} :</strong> {{ implode(', ', $creneaux) }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-lg-5">
                @if($demande->statut === 'affectee')
                    <div class="card">
                        <div class="card-body">
                            <h5>Confirmer un rendez-vous</h5>
                            <form action="{{ route('medecin.demandes.confirmer', $demande) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Date et heure</label>
                                    <input type="datetime-local" name="date_heure" class="form-control" required>
                                    @error('date_heure') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Durée (minutes)</label>
                                    <input type="number" name="duree" class="form-control" value="30" min="10" max="180">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Notes (optionnel)</label>
                                    <textarea name="notes" class="form-control" rows="2"></textarea>
                                </div>
                                <button type="submit" class="btn btn-success w-100">Confirmer le rendez-vous</button>
                            </form>
                        </div>
                    </div>
                @elseif($demande->statut === 'confirmee')
                    <div class="alert alert-success">
                        Rendez-vous déjà confirmé pour cette demande.
                    </div>
                @else
                    <div class="alert alert-secondary">
                        Cette demande n'est pas encore affectée ou a un autre statut ({{ $demande->statut }}).
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection