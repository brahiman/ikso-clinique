<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdonnanceDetail extends Model
{
    protected $fillable = [
        'ordonnance_id',
        'medicament_id',
        'quantite',
        'posologie',
        'duree_jours',
        'frequence',
        'moment',
        'instructions',
        'dosage_prescrit'
    ];

    public function ordonnance()
    {
        return $this->belongsTo(Ordonnance::class);
    }

    public function medicament()
    {
        return $this->belongsTo(Medicament::class);
    }
}
