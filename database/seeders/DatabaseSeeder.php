<?php

namespace Database\Seeders;

use App\Http\Controllers\Patient\DossierMedicalController;
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
            DossierMedicalController::class,
        ]);
    }
}
