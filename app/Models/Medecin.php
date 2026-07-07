<?php

namespace App\Models;

use App\Models\Consultation;
use App\Models\DemandeConsultation;
use App\Models\Patient;
use App\Models\RendezVous;
use App\Models\Specialite;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medecin extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'specialite_id', 'matricule', 'telephone',
        'disponibilite', 'statut'
    ];

    // protected $casts = [
    //     'disponibilite' => 'array',
    // ];
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
            set: fn ($value) => json_encode($value),
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
public function rendezVous()
{
    return $this->hasMany(RendezVous::class);
}
    public function specialite()
    {
        return $this->belongsTo(Specialite::class);
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
