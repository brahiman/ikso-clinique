<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TypeExamen extends Model
{
    use HasFactory;
protected $table = 'type_examens';
    protected $fillable = [
        'nom',
        'description',
    ];

    /**
     * Demandes contenant cet examen.
     */
    public function demandesExamens()
    {
        return $this->belongsToMany(
            DemandeExamen::class,
            'demande_type_examen',
            'type_examen_id',
            'demande_examen_id'
        )->using(DemandeTypeExamen::class)
        ->withPivot(
            'observation',
            'resultat',
            'date_resultat'
        )->withTimestamps();
    }
}