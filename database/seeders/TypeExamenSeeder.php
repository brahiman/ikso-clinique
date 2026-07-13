<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypeExamenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('types_examens')->insert([
            [
                'nom' => 'Analyse de sang',
                'description' => 'Examen biologique permettant d’évaluer différents paramètres sanguins.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Analyse d’urine',
                'description' => 'Examen permettant de détecter des anomalies urinaires.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Radiographie',
                'description' => 'Imagerie médicale utilisant les rayons X.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Échographie',
                'description' => 'Examen d’imagerie utilisant les ultrasons.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Scanner',
                'description' => 'Tomodensitométrie pour visualiser les organes internes.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'IRM',
                'description' => 'Imagerie par Résonance Magnétique.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Électrocardiogramme (ECG)',
                'description' => 'Enregistrement de l’activité électrique du cœur.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Test de glycémie',
                'description' => 'Mesure du taux de glucose dans le sang.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Test de grossesse',
                'description' => 'Détection de l’hormone hCG.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Coproculture',
                'description' => 'Analyse des selles pour détecter des infections intestinales.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}