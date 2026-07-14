<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AntecedentMedical extends Model
{
    use HasFactory;

    protected $table = 'antecedents_medicaux';

    protected $fillable = [
        'dossier_medical_id',
        'type',
        'nom',
        'description',
        'date_evenement',
        'gravite',
        'actif'
    ];

    protected $casts = [
        'date_evenement' => 'date',
    ];

   
    public function dossierMedical()
    {
        return $this->belongsTo(DossierMedical::class, 'dossier_medical_id');
    }
}
