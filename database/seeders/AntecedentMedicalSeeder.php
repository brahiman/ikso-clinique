<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AntecedentMedical;
use App\Models\Patient;

class AntecedentMedicalSeeder extends Seeder
{
    public function run()
    {
        $patients = Patient::all();

        foreach ($patients as $patient) {
            AntecedentMedical::create([
                'patient_id' => $patient->id,   // ← Important
                'dossier_medical_id' => 1,
                'type' => 'operation',
                'nom' => 'Appendicectomie',
                'description' => 'Retrait de l’appendice en 2015.',
                'date_evenement' => '2015-07-15',
                'gravite' => 'faible',
                'actif' => true,
            ]);

            AntecedentMedical::create([
                'patient_id' => $patient->id,
                'dossier_medical_id' => 2,
                'type' => 'maladie',
                'nom' => 'Hypertension artérielle',
                'description' => 'Diagnostiquée en 2020',
                'date_evenement' => '2020-01-10',
                'gravite' => 'moyenne',
                'actif' => true,
            ]);

            AntecedentMedical::create([
                'patient_id' => $patient->id,
                'dossier_medical_id' => 1,
                'type' => 'allergie',
                'nom' => 'Pénicilline',
                'description' => 'Réaction cutanée',
                'date_evenement' => '2018-05-20',
                'gravite' => 'haute',
                'actif' => true,
            ]);
        }

        $this->command->info('✅ Antécédents médicaux créés avec succès !');
    }
}
