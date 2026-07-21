<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DemandeExamen extends Model
{
    use HasFactory;
    protected $table = 'demande_examens';

    protected $fillable = [
        'patient_id',
        'consultation_id',
        'date_demande',
        'instructions',
        'statut',
    ];

    protected $casts = [
        'date_demande' => 'date',
    ];

    /**
     * Patient concerné.
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Consultation à l'origine de la demande.
     */
    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }

    /**
     * Examens demandés.
     */
    public function typesExamens()
    {
        return $this->belongsToMany(
            TypeExamen::class,
            'demande_type_examen',
            'demande_examen_id',
            'type_examen_id'
        )->using(DemandeTypeExamen::class)
        ->withPivot(
            'observation',
            'resultat',
            'date_resultat'
        )->withTimestamps();
    }
    public function details()
    {
        return $this->hasMany(DemandeTypeExamen::class, 'demande_examen_id');
    }


}
