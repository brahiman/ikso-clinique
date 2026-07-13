<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Medecin;
use App\Models\Specialite;

class MedecinSeeder extends Seeder
{
    public function run()
    {
        // Création des spécialités si elles n'existent pas
        $specialites = [
            'Cardiologie', 'Dermatologie', 'Pédiatrie', 'Gynécologie',
            'Ophtalmologie', 'Médecine Générale', 'Neurologie', 'Orthopédie'
        ];

        foreach ($specialites as $nom) {
            Specialite::firstOrCreate(['nom' => $nom]);
        }

        // Médecin 1
        $user1 = User::firstOrCreate(
            ['email' => 'dr.diallo@clinique.com'],
            [
                'name' => 'Dr. Amadou Diallo',
                'password' => bcrypt('password123'),
                'telephone' => '0709988776',
                'is_active' => true,
            ]
        );
        $user1->assignRole('medecin');

        Medecin::firstOrCreate(
            ['user_id' => $user1->id],
            [
                'specialite_id' => Specialite::where('nom', 'Cardiologie')->first()->id,
                'matricule' => 'MED001',
                'telephone' => '0709988776',
                'disponibilite' => json_encode([
                    'lundi' => ['08:00-12:00', '14:00-18:00'],
                    'mardi' => ['09:00-13:00', '15:00-17:00'],
                    'mercredi' => ['08:00-12:00']
                ]),
                'statut' => 'actif'
            ]
        );

        // Médecin 2
        $user2 = User::firstOrCreate(
            ['email' => 'dr.sow@clinique.com'],
            [
                'name' => 'Dr. Fatou Sow',
                'password' => bcrypt('password123'),
                'telephone' => '0775544332',
                'is_active' => true,
            ]
        );
        $user2->assignRole('medecin');

        Medecin::firstOrCreate(
            ['user_id' => $user2->id],
            [
                'specialite_id' => Specialite::where('nom', 'Gynécologie')->first()->id,
                'matricule' => 'MED002',
                'telephone' => '0775544332',
                'disponibilite' => json_encode([
                    'lundi' => ['09:00-13:00'],
                    'mercredi' => ['08:00-12:00', '14:00-18:00'],
                    'vendredi' => ['10:00-16:00']
                ]),
                'statut' => 'actif'
            ]
        );

        $this->command->info('✅ Médecins et Spécialités créés avec succès !');

        //creer quelque medicaments
        
    }
}
