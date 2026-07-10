<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExamenComplementaire;
use App\Models\Patient;
use App\Models\Consultation;

class ExamenSeeder extends Seeder
{
    public function run()
    {
        $patients = Patient::all();

        foreach ($patients as $patient) {
            ExamenComplementaire::create([
                'patient_id' => $patient->id,
                'consultation_id' => Consultation::where('patient_id', $patient->id)->first()?->id,
                'type_examen' => 'Analyse sanguine',
                'description' => 'NFS + CRP + Glycémie',
                'resultats' => 'Globules blancs : 12.5 G/L, CRP : 45 mg/L',
                'date_demande' => now()->subDays(15),
                'date_resultat' => now()->subDays(12),
                'statut' => 'termine'
            ]);

            ExamenComplementaire::create([
                'patient_id' => $patient->id,
                'type_examen' => 'Radiographie thoracique',
                'description' => 'Contrôle pulmonaire',
                'resultats' => 'Aucune anomalie détectée',
                'date_demande' => now()->subDays(5),
                'date_resultat' => now(),
                'statut' => 'termine'
            ]);
        }

        $this->command->info('✅ Examens Complémentaires créés avec succès !');
    }
}
