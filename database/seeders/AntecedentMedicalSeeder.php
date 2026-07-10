<?php

namespace Database\Seeders;

use App\Models\AntecedentMedical;
use App\Models\Consultation;
use App\Models\Patient;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AntecedentMedicalSeeder extends Seeder
{
    public function run()
    {
        $patients = Patient::all();

        foreach ($patients as $patient) {
            // Création du dossier médical principal (si tu as une table dossiers_medicaux)
            // Sinon, on ajoute directement les antécédents

            // Antécédents Médicaux
            AntecedentMedical::create([
                'patient_id' => $patient->id,
                'type' => 'allergie',
                'nom' => 'Pénicilline',
                'description' => 'Réaction cutanée sévère',
                'date_evenement' => '2020-05-12',
                'gravite' => 'haute'
            ]);

            AntecedentMedical::create([
                'patient_id' => $patient->id,
                'type' => 'maladie',
                'nom' => 'Hypertension artérielle',
                'description' => 'Diagnostiquée en 2018, traitée par Losartan',
                'date_evenement' => '2018-03-01',
                'gravite' => 'moyenne'
            ]);

            AntecedentMedical::create([
                'patient_id' => $patient->id,
                'type' => 'operation',
                'nom' => 'Appendicectomie',
                'description' => 'Opération en urgence',
                'date_evenement' => '2015-11-20',
                'gravite' => 'faible'
            ]);

            // Consultations de test
            Consultation::create([
                'patient_id' => $patient->id,
                'medecin_id' => 1, // Dr. Diallo
                'date_consultation' => now()->subDays(30),
                'diagnostic' => 'Gastrite aiguë',
                'observations' => 'Douleurs épigastriques depuis 10 jours',
                'traitement' => 'Oméprazole 20mg/jour pendant 14 jours',
                'statut' => 'terminee'
            ]);
        }

        $this->command->info('✅ Dossiers médicaux et antécédents créés avec succès pour tous les patients !');
    }
}
