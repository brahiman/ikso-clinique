<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Specialite extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'description'];

    public function medecins()
    {
        return $this->hasMany(Medecin::class);
    }

    public function medecins2(): BelongsToMany
    {
        return $this->belongsToMany(Medecin::class, 'medecins_specialites', 'specialite_id', 'medecin_id');
    }
}
