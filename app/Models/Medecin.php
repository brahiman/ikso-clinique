<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Medecin extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'specialite_id', 'matricule', 'telephone',
        'disponibilite', 'statut'
    ];

    // protected $casts = [
    //     'disponibilite' => 'array',
    // ];
    public function specialites(): BelongsToMany
    {
        return $this->belongsToMany(Specialite::class, 'medecins_specialites');
    }

    protected function disponibilite(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if (!$value) return [];
                $decoded = json_decode($value, true);
                // Sécurité pour les anciennes lignes encodées deux fois
                if (is_string($decoded)) {
                    $decoded = json_decode($decoded, true);
                }
                return $decoded ?? [];
            },
            set: fn($value) => json_encode($value),
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function rendezVous()
    {
        return $this->hasMany(RendezVous::class);
    }

    public function patients()
    {
        return $this->belongsToMany(Patient::class, 'patient_medecin');
    }

    public function consultations()
    {
        return $this->hasMany(Consultation::class);
    }

    // Relation avec les demandes de consultation
    public function demandesConsultation()
    {
        return $this->hasMany(DemandeConsultation::class);

    }
}
