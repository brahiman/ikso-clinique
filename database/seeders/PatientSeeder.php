<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Patient;
use App\Models\User;

class PatientSeeder extends Seeder
{
    public function run()
    {
        // Patient avec compte utilisateur
        $user = User::firstOrCreate(
            ['email' => 'patient1@clinique.com'],
            [
                'name' => 'Fatou Ndiaye',
                'password' => bcrypt('password123'),
                'telephone' => '0771234567',
                'sexe' => 'F',
                'date_naissance' => '1995-03-15',
                'is_active' => true,
            ]
        );
        $user->assignRole('patient');

        $patient = Patient::firstOrCreate(
            ['telephone' => '0771234567'],
            [
                'nom' => 'Ndiaye',
                'prenom' => 'Fatou',
                'sexe' => 'F',
                'date_naissance' => '1995-03-15',
                'email' => 'patient1@clinique.com',
                'adresse' => 'Médina, Dakar',
                'groupe_sanguin' => 'O+',
                'contact_urgence_nom' => 'Moussa Ndiaye',
                'contact_urgence_telephone' => '0789456123',
                'created_by' => 1,
            ]
        );

        // Associer le user au patient (clé étrangère dans patients)
        if (!$patient->user_id) {
            $patient->update(['user_id' => $user->id]);
        }

        // Autres patients (sans compte utilisateur)
        Patient::firstOrCreate(
            ['telephone' => '0765432198'],
            [
                'nom' => 'Diop',
                'prenom' => 'Mamadou',
                'sexe' => 'M',
                'date_naissance' => '1988-11-20',
                'adresse' => 'Guédiawaye',
                'groupe_sanguin' => 'A+',
                'contact_urgence_nom' => 'Awa Diop',
                'contact_urgence_telephone' => '0778899001',
                'created_by' => 1,
            ]
        );

        Patient::firstOrCreate(
            ['telephone' => '0701122334'],
            [
                'nom' => 'Ba',
                'prenom' => 'Aissatou',
                'sexe' => 'F',
                'date_naissance' => '2002-07-05',
                'adresse' => 'Yoff',
                'groupe_sanguin' => 'B-',
                'contact_urgence_nom' => 'Ibrahima Ba',
                'contact_urgence_telephone' => '0781122334',
                'created_by' => 1,
            ]
        );

        $this->command->info('✅ Patients de test créés avec succès !');
    }
}
