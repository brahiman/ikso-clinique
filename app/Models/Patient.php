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
        'contact_urgence_telephone', 'created_by', 'responsable_id'
    ];

    protected $casts = [
        'date_naissance' => 'date',
    ];

    // ====================== RELATIONS ======================

    /**
     * Le responsable (parent, conjoint, tuteur, etc.) qui a créé le compte
     */
    public function responsable()
    {
        return $this->belongsTo(User::class, 'responsable_id');
    }

    /**
     * Si le patient a un compte utilisateur personnel
     */
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

    // ====================== HELPERS ======================

    public function isChildOrDependent()
    {
        return !is_null($this->responsable_id);
    }

    public function hasPersonalAccount()
    {
        return !is_null($this->user_id);
    }

    public function dossierMedical()
    {
        return $this->hasOne(DossierMedical::class);
    }

    public function antecedents()
    {
        return $this->hasMany(AntecedentMedical::class);
    }

    public function examens()
    {
        return $this->hasMany(ExamenComplementaire::class);
    }

    public function ordonnances()
    {
        return $this->hasMany(Ordonnance::class);
    }
}

