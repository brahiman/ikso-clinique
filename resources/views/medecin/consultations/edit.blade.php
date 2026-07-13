@extends('layouts.master')

@section('content')
<div class="page-content">
    <div class="container-fluid">
        <h2 class="mb-4">Consultation — {{ $consultation->patient->prenom }} {{ $consultation->patient->nom }}</h2>

        <div class="card">
            <div class="card-body">
                @if($consultation->isDirecte())
                    <p><strong>Motif (accueil direct) :</strong> {{ $consultation->motif_direct ?? '—' }}</p>
                @else
                    <p><strong>Motif du rendez-vous :</strong> {{ $consultation->rendezVous->motif ?? '—' }}</p>
                @endif

                <form action="{{ route('medecin.consultations.update', $consultation) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Diagnostic</label>
                        <textarea name="diagnostic" class="form-control" rows="3">{{ old('diagnostic', $consultation->diagnostic) }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Observations</label>
                        <textarea name="observations" class="form-control" rows="3">{{ old('observations', $consultation->observations) }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Traitement</label>
                        <textarea name="traitement" class="form-control" rows="3">{{ old('traitement', $consultation->traitement) }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Recommandations</label>
                        <textarea name="recommandations" class="form-control" rows="3">{{ old('recommandations', $consultation->recommandations) }}</textarea>
                    </div>

                    <button type="submit" name="action" value="continuer" class="btn btn-outline-secondary">
                        Enregistrer et continuer plus tard
                    </button>
                    <button type="submit" name="action" value="terminer" class="btn btn-success">
                        Terminer la consultation
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection