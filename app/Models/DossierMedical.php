<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DossierMedical extends Model
{
    use HasFactory;
    protected $table = 'dossiers_medicaux';
    protected $fillable = [
        'patient_id',
        'antecedents_familiaux',
        'allergies',
        'vaccins',
        'notes_generales'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
