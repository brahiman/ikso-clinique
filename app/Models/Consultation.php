<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Consultation extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'medecin_id',
        'rendez_vous_id',
        'date_consultation',
        'diagnostic',
        'observations',
        'traitement',
        'recommandations',
        'statut',
        'est_urgence',
        'motif_direct',
        'notes_accueil'
    ];

    protected $casts = [
        'date_consultation' => 'datetime',
        'est_urgence' => 'boolean',
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

    public function rendezVous()
    {
        return $this->belongsTo(RendezVous::class);
    }
    public function demandesExamens()
    {
        return $this->hasMany(DemandeExamen::class);
    }
    public function ordonnances()
    {
        return $this->hasMany(Ordonnance::class);
    }   

    // ====================== SCOPES DE SÉCURITÉ ======================

    /**
     * Filtre uniquement les consultations du médecin connecté
     */
    public function scopeForCurrentMedecin($query)
    {
        if (auth()->check() && auth()->user()->isMedecin()) {
            return $query->where('medecin_id', auth()->user()->medecin->id);
        }
        return $query;
    }

    /**
     * Consultations directes (sans rendez-vous)
     */
    public function scopeDirectes($query)
    {
        return $query->whereNull('rendez_vous_id');
    }

    /**
     * Consultations urgentes
     */
    public function scopeUrgences($query)
    {
        return $query->where('est_urgence', true);
    }

    // ====================== MÉTHODES HELPER ======================

    public function isDirecte(): bool
    {
        return is_null($this->rendez_vous_id);
    }

    public function isUrgence(): bool
    {
        return $this->est_urgence;
    }

    public function isTerminee(): bool
    {
        return $this->statut === 'terminee';
    }

    /**
     * Résumé court pour affichage
     */
    public function getResumeAttribute()
    {
        return Str::limit($this->diagnostic ?? $this->observations ?? 'Consultation sans diagnostic', 80);
    }
}
