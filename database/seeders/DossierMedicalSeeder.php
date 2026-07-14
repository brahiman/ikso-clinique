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
                    'notes_generales' => 'Dossier médical initial pour le patient ' . $patient->nom . ' ' . $patient->prenom,
                ]
            );
        }

        $this->command->info('✅ Dossiers Médicaux créés avec succès !');
    }
}
