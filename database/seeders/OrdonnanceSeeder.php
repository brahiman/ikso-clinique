<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ordonnance;
use App\Models\Patient;
use App\Models\Medecin;

class OrdonnanceSeeder extends Seeder
{
    public function run()
    {
        $patients = Patient::all();
        $medecins = Medecin::all();

        foreach ($patients as $patient) {
            // Ordonnance 1
            Ordonnance::create([
                'patient_id' => $patient->id,
                'medecin_id' => $medecins->random()->id,
                'consultation_id' => null, // Peut être lié à une consultation
                'medicaments' => 'Oméprazole 20mg, Paracétamol 1g',
                'posologie' => '1 comprimé matin et soir pendant 14 jours',
                'duree_jours' => 14,
                'date_prescription' => now()->subDays(20),
                'notes' => 'Pour gastrite',
            ]);

            // Ordonnance 2
            Ordonnance::create([
                'patient_id' => $patient->id,
                'medecin_id' => $medecins->random()->id,
                'consultation_id' => null,
                'medicaments' => 'Losartan 50mg',
                'posologie' => '1 comprimé par jour le matin',
                'duree_jours' => 30,
                'date_prescription' => now()->subDays(5),
                'notes' => 'Pour hypertension',
            ]);
        }

        $this->command->info('✅ Ordonnances créées avec succès pour tous les patients !');
    }
}
