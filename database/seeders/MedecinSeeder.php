<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Medecin;

class MedecinSeeder extends Seeder
{
    public function run()
    {
        // Dr. Amadou Diallo
        $user = User::firstOrCreate(
            ['email' => 'dr.diallo@clinique.com'],
            [
                'name' => 'Dr. Amadou Diallo',
                'password' => bcrypt('password123'),
                'telephone' => '0709988776',
                'is_active' => true,
            ]
        );
        $user->assignRole('medecin');

        Medecin::firstOrCreate(
            ['user_id' => $user->id],
            [
                'specialite_id' => 1, // Cardiologie par exemple
                'matricule' => 'MED001',
                'telephone' => '0709988776',
                'disponibilite' => json_encode([
                    'lundi' => ['08:00-12:00', '14:00-18:00'],
                    'mardi' => ['09:00-13:00']
                ]),
                'statut' => 'actif'
            ]
        );

        $this->command->info('✅ Médecins créés et liés avec succès !');
    }
}
