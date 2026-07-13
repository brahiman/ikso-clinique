<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ordonnance extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'consultation_id',
        'medecin_id',
        'medicaments',
        'posologie',
        'duree_jours',
        'date_prescription',
        'notes'
    ];

    protected $casts = [
        'date_prescription' => 'date',
    ];
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function medecin()
    {
        return $this->belongsTo(Medecin::class);
    }

    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }
    public function medicaments()
    {
        //ici la table intermediaire sappele :ordonnance_details
        return $this->belongsToMany(Medicament::class, 'medicament_ordonnance', 'ordonnance_id', 'medicament_id')
                    ->withPivot('quantite', 'posologie', 'duree_jours');
    }
    public function details()
    {
        return $this->hasMany(OrdonnanceDetail::class);
    }
}