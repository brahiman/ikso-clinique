<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicament extends Model
{
    protected $fillable = [
        'nom',
        'forme',
        'dosage',
        'fabricant',
        'description',
    ];

    public function ordonnances()
    {
        //ici la table intermediaire sappele :ordonnance_details
        return $this->belongsToMany(Ordonnance::class, 'medicament_ordonnance', 'medicament_id', 'ordonnance_id')
                    ->withPivot('quantite', 'posologie', 'duree_jours');
    }
    public function ordonnanceDetails()
    {
        return $this->hasMany(OrdonnanceDetail::class);
    }
}
