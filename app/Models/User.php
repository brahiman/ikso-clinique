<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, SoftDeletes, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'telephone',
        'avatar',
        'adresse',
        'date_naissance',
        'sexe',
        'matricule',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'date_naissance' => 'date',
        'is_active' => 'boolean',
        'last_login_at' => 'datetime',
    ];

    // Relations
    public function medecin()
    {
        return $this->hasOne(Medecin::class);
    }

    public function patient()
    {
        return $this->hasOne(Patient::class);
    }

    // Helpers pour les rôles
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function isSecretaire(): bool
    {
        return $this->hasRole('secretaire');
    }

    public function isMedecin(): bool
    {
        return $this->hasRole('medecin');
    }

    public function isPatient(): bool
    {
        return $this->hasRole('patient');
    }

    /**
     * Retourne le profil selon le rôle
     */
    public function profile()
    {
        if ($this->isMedecin()) return $this->medecin;
        if ($this->isPatient()) return $this->patient;
        return null;
    }
}
