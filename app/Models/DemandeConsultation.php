<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DemandeConsultation extends Model
{
    use HasFactory;
    protected $table = 'demandes_consultations';
    protected $fillable = [
        'patient_id',
        'service_souhaite',
        'motif',
        'symptomes',
        'urgence',
        'disponibilite_patient',
        'date_souhaitee',
        'heure_souhaitee',
        'pref_medecin',
        'statut',
        'secretaire_id',
        'medecin_id',
        'date_affectation'
    ];

    protected $casts = [
        'date_affectation' => 'datetime',
        'urgence' => 'string',
        'statut' => 'string',
        'date_souhaitee' => 'date',
    ];

    // ====================== RELATIONS ======================

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function medecin()
    {
        return $this->belongsTo(Medecin::class);
    }

    public function secretaire()
    {
        return $this->belongsTo(User::class, 'secretaire_id');
    }

    public function rendezVous()
    {
        return $this->hasOne(RendezVous::class);
    }

    // ====================== SCOPES ======================

    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }

    public function scopeAffectees($query)
    {
        return $query->where('statut', 'affectee');
    }

    public function scopeUrgentes($query)
    {
        return $query->where('urgence', 'haute');
    }

    // ====================== HELPERS ======================

    public function isEnAttente(): bool
    {
        return $this->statut === 'en_attente';
    }

    public function getUrgenceBadgeAttribute()
    {
        return match($this->urgence) {
            'haute' => '<span class="badge bg-danger">Urgente</span>',
            'moyenne' => '<span class="badge bg-warning">Moyenne</span>',
            default => '<span class="badge bg-info">Normale</span>'
        };
    }
}
