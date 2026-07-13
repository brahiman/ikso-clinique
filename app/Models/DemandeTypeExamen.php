<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class DemandeTypeExamen extends Pivot
{
    protected $table = 'demande_type_examen';

    protected $fillable = [
        'demande_examen_id',
        'type_examen_id',
        'observation',
        'resultat',
        'date_resultat',
    ];

    protected $casts = [
        'date_resultat' => 'date',
    ];
    public function demandeExamen()
    {
        return $this->belongsTo(DemandeExamen::class, 'demande_examen_id');
    }
    public function typeExamen()
    {
        return $this->belongsTo(TypeExamen::class, 'type_examen_id');
    }
}