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
            // Ajoute ici tous tes autres seeders
        ]);
        $this->call([
            RolePermissionSeeder::class,
           // MedecinSeeder::class,
            PatientSeeder::class,

        ]);
    }
}
