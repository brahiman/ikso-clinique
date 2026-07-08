<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AntecedentMedical extends Model
{
    use HasFactory;

    protected $table = 'antecedents_medicaux';

    protected $fillable = [
        'patient_id',
        'type',
        'nom',
        'description',
        'date_evenement',
        'gravite'
    ];

    protected $casts = [
        'date_evenement' => 'date',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
