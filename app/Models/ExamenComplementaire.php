<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamenComplementaire extends Model
{
    use HasFactory;
    protected $table = 'examens_complementaires';

    protected $fillable = [
        'patient_id',
        'consultation_id',
        'type_examen',
        'description',
        'resultats',
        'date_demande',
        'date_resultat'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }
}
