<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RendezVous extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'medecin_id',
        'demande_consultation_id',
        'date_heure',
        'duree',
        'statut',
        'motif',
        'notes',
        'created_by'
    ];

    protected $casts = [
        'date_heure' => 'datetime',
        'duree' => 'integer',
        'statut' => 'string',
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

    public function demandeConsultation()
    {
        return $this->belongsTo(DemandeConsultation::class);
    }

    public function consultation()
    {
        return $this->hasOne(Consultation::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ====================== SCOPES DE SÉCURITÉ ======================

    /**
     * Rendez-vous du médecin connecté
     */
    public function scopeForCurrentMedecin($query)
    {
        if (auth()->check() && auth()->user()->isMedecin()) {
            return $query->where('medecin_id', auth()->user()->medecin->id);
        }
        return $query;
    }

    /**
     * Rendez-vous du jour
     */
    public function scopeAujourdhui($query)
    {
        return $query->whereDate('date_heure', today());
    }

    /**
     * Rendez-vous à venir
     */
    public function scopeAVenir($query)
    {
        return $query->where('date_heure', '>=', now())
            ->whereIn('statut', ['planifie', 'confirme']);
    }

    // ====================== HELPERS ======================

    public function isToday(): bool
    {
        return $this->date_heure->isToday();
    }

    public function isPassed(): bool
    {
        return $this->date_heure->isPast();
    }

    public function getStatusColorAttribute()
    {
        return match($this->statut) {
            'planifie' => 'warning',
            'confirme' => 'info',
            'en_cours' => 'primary',
            'termine' => 'success',
            'annule' => 'danger',
            default => 'secondary'
        };
    }
}
