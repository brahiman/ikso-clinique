@extends('layouts.master')

@section('content')
<div class="page-content">
    <div class="container-fluid">
        
        <!-- En-tête de la page -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ url()->previous() }}" class="btn btn-sm btn-light border rounded-circle shadow-sm p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;" title="Retour">
                    <i class="bi bi-arrow-left fs-5"></i>
                </a>
                <div>
                    <div class="d-flex align-items-center gap-2">
                        <h3 class="fw-bold mb-0 text-dark">Demande de consultation</h3>
                        <span class="badge bg-{{ $demande->urgence === 'haute' ? 'danger' : ($demande->urgence === 'moyenne' ? 'warning text-dark' : 'secondary') }}-subtle text-{{ $demande->urgence === 'haute' ? 'danger' : ($demande->urgence === 'moyenne' ? 'warning' : 'secondary') }} border border-{{ $demande->urgence === 'haute' ? 'danger' : ($demande->urgence === 'moyenne' ? 'warning' : 'secondary') }} px-2 py-1 rounded-pill text-uppercase" style="font-size: 0.75rem;">
                            <i class="bi bi-exclamation-circle me-1"></i>Urgence {{ $demande->urgence }}
                        </span>
                    </div>
                    <p class="text-muted mb-0 small">Réf. demande #{{ $demande->id }} &bull; Soumise le {{ $demande->created_at ? $demande->created_at->format('d/m/Y à H:i') : '—' }}</p>
                </div>
            </div>
            
            <div class="d-flex align-items-center gap-2">
                @if($demande->statut === 'affectee')
                    <span class="badge bg-warning-subtle text-warning border border-warning px-3 py-2 rounded-pill fw-medium">
                        <i class="bi bi-hourglass-split me-1"></i>En attente de confirmation
                    </span>
                @elseif($demande->statut === 'confirmee')
                    <span class="badge bg-success-subtle text-success border border-success px-3 py-2 rounded-pill fw-medium">
                        <i class="bi bi-check2-circle me-1"></i>Confirmée
                    </span>
                @else
                    <span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-pill fw-medium">
                        {{ ucfirst($demande->statut) }}
                    </span>
                @endif
            </div>
        </div>

        <div class="row g-4">
            <!-- Colonne de gauche : Infos Patient & Demande -->
            <div class="col-lg-7">
                
                <!-- Carte Profil Patient & Demande -->
                <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                    <div class="card-header bg-white border-bottom py-3 px-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar-md bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold fs-4" style="width: 52px; height: 52px;">
                                {{ strtoupper(substr($demande->patient->prenom, 0, 1)) }}{{ strtoupper(substr($demande->patient->nom, 0, 1)) }}
                            </div>
                            <div>
                                <h5 class="mb-1 text-dark fw-bold">{{ $demande->patient->prenom }} {{ $demande->patient->nom }}</h5>
                                <div class="d-flex flex-wrap align-items-center gap-3 text-muted small">
                                    <span><i class="bi bi-telephone text-primary me-1"></i> <a href="tel:{{ $demande->patient->telephone }}" class="text-decoration-none text-muted">{{ $demande->patient->telephone }}</a></span>
                                    @if(isset($demande->patient->email))
                                        <span><i class="bi bi-envelope text-primary me-1"></i> {{ $demande->patient->email }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <h6 class="text-uppercase text-muted fw-semibold mb-3 small tracking-wider">Détails de la consultation</h6>
                        
                        <div class="row g-3 mb-4">
                            <div class="col-sm-6">
                                <div class="p-3 bg-light rounded-3 h-100 border border-light-subtle">
                                    <div class="text-muted small mb-1">Service souhaité</div>
                                    <div class="fw-semibold text-dark fs-6 d-flex align-items-center gap-2">
                                        <i class="bi bi-hospital text-primary"></i>
                                        {{ $demande->service_souhaite ?? 'Consultation générale' }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="p-3 bg-light rounded-3 h-100 border border-light-subtle">
                                    <div class="text-muted small mb-1">Mode de consultation</div>
                                    <div class="fw-semibold text-dark fs-6 d-flex align-items-center gap-2">
                                        @if(Str::contains(strtolower($demande->mode_consultation), 'video') || Str::contains(strtolower($demande->mode_consultation), 'tele'))
                                            <i class="bi bi-camera-video text-info"></i>
                                        @else
                                            <i class="bi bi-geo-alt text-success"></i>
                                        @endif
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">
                                            {{ $demande->mode_consultation }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Symptômes -->
                        <div class="mb-4">
                            <label class="text-muted small fw-semibold text-uppercase mb-2">Symptômes & Motif</label>
                            <div class="p-3 bg-light-subtle rounded-3 border">
                                <p class="mb-0 text-secondary" style="white-space: pre-line;">{{ $demande->symptomes ?: 'Aucun symptôme renseigné.' }}</p>
                            </div>
                        </div>

                        <!-- Disponibilités souhaitées par le patient -->
                        <div class="p-3 bg-info-subtle border border-info-subtle rounded-3 d-flex align-items-center gap-3">
                            <i class="bi bi-calendar-event text-info fs-4"></i>
                            <div>
                                <div class="fw-bold text-dark small">Disponibilité indiquée par le patient</div>
                                <div class="text-secondary small">{{ $demande->disponibilite_patient ?? 'Flexible / Non spécifié' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Disponibilités habituelles du médecin -->
                @if($demande->medecin && $demande->medecin->disponibilite)
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <i class="bi bi-clock-history text-primary fs-5"></i>
                                <h6 class="fw-bold mb-0 text-dark">Mes disponibilités habituelles</h6>
                            </div>
                            <div class="row g-2">
                                @foreach($demande->medecin->disponibilite as $jour => $creneaux)
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center justify-content-between p-2 px-3 bg-light rounded-3 border border-light-subtle">
                                            <span class="fw-medium text-capitalize text-dark small">{{ $jour }}</span>
                                            <div class="d-flex flex-wrap gap-1 justify-content-end">
                                                @foreach((array)$creneaux as $creneau)
                                                    <span class="badge bg-white text-dark border shadow-2xs font-monospace small">{{ $creneau }}</span>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Colonne de droite : Action / Validation -->
            <div class="col-lg-5">
                @if($demande->statut === 'affectee')
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden sticky-top" style="top: 20px;">
                        <div class="card-header bg-primary bg-gradient text-white py-3 px-4">
                            <h5 class="mb-0 fw-bold d-flex align-items-center gap-2 text-white">
                                <i class="bi bi-calendar-check"></i> Fixer le rendez-vous
                            </h5>
                            <small class="text-white-50">Définissez l'horaire pour notifier le patient</small>
                        </div>
                        
                        <div class="card-body p-4">
                            <form action="{{ route('medecin.demandes.confirmer', $demande) }}" method="POST">
                                @csrf
                                
                                <!-- Date & Heure -->
                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-dark small">Date et heure du rendez-vous <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-calendar3"></i></span>
                                        <input type="datetime-local" name="date_heure" class="form-control border-start-0 ps-0 @error('date_heure') is-invalid @enderror" value="{{ old('date_heure', $demande->date_souhaitee) }}" required>
                                    </div>
                                    @error('date_heure')
                                        <div class="text-danger small mt-1"><i class="bi bi-exclamation-triangle me-1"></i>{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Durée -->
                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-dark small d-flex justify-content-between">
                                        <span>Durée estimée</span>
                                        <span class="text-muted" id="duree-label">30 min</span>
                                    </label>
                                    <div class="input-group mb-2">
                                        <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-hourglass-top"></i></span>
                                        <input type="number" id="duree-input" name="duree" class="form-control border-start-0 ps-0" value="{{ old('duree', 30) }}" min="10" max="180" step="5" required>
                                        <span class="input-group-text bg-light text-muted">minutes</span>
                                    </div>
                                    <!-- Raccourcis de durée -->
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-xs btn-outline-secondary py-1 px-2 rounded-2 small" onclick="setDuree(15)">15m</button>
                                        <button type="button" class="btn btn-xs btn-outline-secondary py-1 px-2 rounded-2 small active" onclick="setDuree(30)">30m</button>
                                        <button type="button" class="btn btn-xs btn-outline-secondary py-1 px-2 rounded-2 small" onclick="setDuree(45)">45m</button>
                                        <button type="button" class="btn btn-xs btn-outline-secondary py-1 px-2 rounded-2 small" onclick="setDuree(60)">1h</button>
                                    </div>
                                </div>

                                <!-- Notes optionnelles -->
                                <div class="mb-4">
                                    <label class="form-label fw-semibold text-dark small">Instructions / Notes pour le patient</label>
                                    <textarea name="notes" class="form-control" rows="3" placeholder="Ex: Se présenter 10 minutes avant avec les bilans précédents...">{{ old('notes') }}</textarea>
                                    <div class="form-text text-muted small">Ces informations seront transmises au patient dans son email de confirmation.</div>
                                </div>

                                <!-- Actions -->
                                <button type="submit" class="btn btn-success btn-lg w-100 fw-semibold shadow-sm d-flex align-items-center justify-content-center gap-2">
                                    <i class="bi bi-check-circle-fill"></i> Confirmer le rendez-vous
                                </button>
                            </form>
                        </div>
                    </div>

                @elseif($demande->statut === 'confirmee')
                    <div class="card border-0 shadow-sm rounded-4 text-center p-4 bg-success-subtle border-success-subtle">
                        <div class="avatar-lg bg-success text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-check-lg fs-2"></i>
                        </div>
                        <h5 class="fw-bold text-success mb-2">Rendez-vous confirmé</h5>
                        <p class="text-secondary small mb-3">Cette demande a déjà été validée. Le patient a été notifié.</p>
                        @if($demande->rendezVous)
                            <div class="bg-white p-3 rounded-3 shadow-sm text-start mb-3 border">
                                <div class="small text-muted mb-1">Date & Heure retenues :</div>
                                <div class="fw-bold text-dark"><i class="bi bi-calendar-event me-2 text-primary"></i>{{ \Carbon\Carbon::parse($demande->rendezVous->date_heure)->translatedFormat('l d F Y à H:i') }}</div>
                            </div>
                        @endif
                        <a href="{{ url()->previous() }}" class="btn btn-outline-success w-100">Retour à la liste</a>
                    </div>

                @else
                    <div class="card border-0 shadow-sm rounded-4 p-4 text-center bg-light">
                        <div class="avatar-lg bg-secondary-subtle text-secondary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-info-circle fs-2"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">Statut : {{ ucfirst($demande->statut) }}</h6>
                        <p class="text-muted small mb-3">Cette demande ne peut pas être confirmée dans son état actuel.</p>
                        <a href="{{ url()->previous() }}" class="btn btn-secondary w-100">Retour</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    function setDuree(minutes) {
        const input = document.getElementById('duree-input');
        const label = document.getElementById('duree-label');
        if (input) {
            input.value = minutes;
            if(label) label.textContent = minutes + ' min';
        }
    }
</script>
@endsection