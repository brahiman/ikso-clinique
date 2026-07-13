<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            RolePermissionSeeder::class,
            //SpecialiteSeeder::class,   // si tu en as un
            PatientSeeder::class,
            MedecinSeeder::class,
            DossierMedicalSeeder::class,
            AntecedentMedicalSeeder::class,
          //  ExamenSeeder::class,
           // OrdonnanceSeeder::class
           MedicamentSeeder::class,
           TypeExamenSeeder::class,
        ]);
      
    }
}
