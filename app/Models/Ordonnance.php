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
        return $this->belongsToMany(
            Medicament::class,
            'ordonnance_details',
            'ordonnance_id',
            'medicament_id'
        )->withPivot(
            'dosage_prescrit',
            'quantite',
            'frequence',
            'moment',
            'duree_jours',
            'instructions'
        );
    }
    public function details()
    {
        return $this->hasMany(OrdonnanceDetail::class);
    }
}