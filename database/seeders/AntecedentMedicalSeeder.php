<?php

namespace Database\Seeders;

use App\Models\AntecedentMedical;
use App\Models\Consultation;
use App\Models\DossierMedical;
use App\Models\Patient;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AntecedentMedicalSeeder extends Seeder
{
    public function run()
    {
        $patients = Patient::all();

        foreach ($patients as $patient) {
            $dossierMedical = $patient->dossierMedical;

            if (!$dossierMedical) {
                $dossierMedical = DossierMedical::create([
                    'patient_id' => $patient->id,
                    'notes_generales' => 'Dossier médical initial pour le patient ' . $patient->nom . ' ' . $patient->prenom,
                ]);
            }

            // Créer des antécédents médicaux pour chaque patient
            AntecedentMedical::firstOrCreate(
                [
                    'dossier_medical_id' => $dossierMedical->id,
                    'type' => 'maladie',
                    'nom' => 'Appendicectomie',
                ],
                [
                    'description' => 'Retrait de l’appendice en 2015.',
                    'date_evenement' => now()->subYears(8),
                    'gravite' => 'faible',
                    'actif' => true,
                ]
            );

            AntecedentMedical::firstOrCreate(
                [
                    'dossier_medical_id' => $dossierMedical->id,
                    'type' => 'allergie',
                    'nom' => 'Pénicilline',
                ],
                [
                    'description' => 'Réaction allergique sévère à la pénicilline.',
                    'date_evenement' => now()->subYears(5),
                    'gravite' => 'haute',
                    'actif' => true,
                ]
            );
        }

        $this->command->info('✅ Dossiers médicaux et antécédents créés avec succès pour tous les patients !');
    }
}
