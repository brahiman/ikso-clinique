@extends('layouts.master')

@section('content')
<div class="page-content">
    <div class="container-fluid">
        <h2 class="mb-4">Nouvelle consultation</h2>

        <div class="card">
            <div class="card-body">
                <p><strong>Patient :</strong> {{ $rendezVous->patient->prenom }} {{ $rendezVous->patient->nom }}</p>
                <p><strong>Motif du rendez-vous :</strong> {{ $rendezVous->motif ?? '—' }}</p>

                <form action="{{ route('medecin.consultations.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="rendez_vous_id" value="{{ $rendezVous->id }}">

                    <div class="mb-3">
                        <label class="form-label">Diagnostic</label>
                        <textarea name="diagnostic" class="form-control" rows="3">{{ old('diagnostic') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Observations</label>
                        <textarea name="observations" class="form-control" rows="3">{{ old('observations') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Traitement</label>
                        <textarea name="traitement" class="form-control" rows="3">{{ old('traitement') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Recommandations</label>
                        <textarea name="recommandations" class="form-control" rows="3">{{ old('recommandations') }}</textarea>
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