<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medecin extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'specialite_id', 'matricule', 'telephone',
        'disponibilite', 'statut'
    ];

    protected $casts = [
        'disponibilite' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
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
}
