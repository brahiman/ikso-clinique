<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DossierMedical;
use App\Models\Patient;

class DossierMedicalSeeder extends Seeder
{
    public function run()
    {
        $patients = Patient::all();

        foreach ($patients as $patient) {
            DossierMedical::firstOrCreate(
                ['patient_id' => $patient->id],
                [
                    'antecedents_familiaux' => 'Père : Hypertension, Mère : Diabète',
                    'allergies' => 'Pénicilline, Arachides',
                    'vaccins' => 'BCG, Hépatite B, Tétanos (à jour)',
                    'notes_generales' => 'Patient suivi pour hypertension depuis 2022.',
                ]
            );
        }

        $this->command->info('✅ Dossiers Médicaux créés avec succès !');
    }
}
