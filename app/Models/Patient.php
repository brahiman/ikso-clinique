<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nom', 'prenom', 'sexe', 'date_naissance', 'telephone',
        'email', 'adresse', 'groupe_sanguin', 'contact_urgence_nom',
        'contact_urgence_telephone', 'created_by', 'user_id'  // Ajoute ceci
    ];

    protected $casts = [
        'date_naissance' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function medecins()
    {
        return $this->belongsToMany(Medecin::class, 'patient_medecin');
    }

    public function consultations()
    {
        return $this->hasMany(Consultation::class);
    }

    public function rendezVous()
    {
        return $this->hasMany(RendezVous::class);
    }
}
