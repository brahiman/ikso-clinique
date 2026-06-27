<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Réinitialise le cache des permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ====================== PERMISSIONS ======================
        $permissions = [
            // Gestion des utilisateurs
            'users.view', 'users.create', 'users.edit', 'users.delete',

            // Patients
            'patients.view', 'patients.create', 'patients.edit', 'patients.delete',

            // Médecins
            'medecins.view', 'medecins.create', 'medecins.edit',

            // Spécialités
            'specialites.view', 'specialites.create', 'specialites.edit',

            // Demandes de consultation
            'demandes.view', 'demandes.create', 'demandes.affecter', 'demandes.valider',

            // Rendez-vous
            'rendezvous.view', 'rendezvous.create', 'rendezvous.edit', 'rendezvous.annuler',

            // Consultations & Dossier médical
            'consultations.view', 'consultations.create', 'consultations.edit',

            // Prescriptions & Examens
            'prescriptions.create', 'examens.create',

            // Statistiques
            'stats.view', 'stats.export',

            // Chat IA
            //'chat_ia.use',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // ====================== RÔLES ======================

        // ADMIN
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all()); // Accès total

        // SECRÉTAIRE
        $secretaire = Role::firstOrCreate(['name' => 'secretaire']);
        $secretaire->givePermissionTo([
            'patients.view', 'patients.create', 'patients.edit',
            'demandes.view', 'demandes.create', 'demandes.affecter',
            'rendezvous.view', 'rendezvous.create', 'rendezvous.edit',
            'medecins.view', 'specialites.view',
            'stats.view'
        ]);

        // MÉDECIN
        $medecin = Role::firstOrCreate(['name' => 'medecin']);
        $medecin->givePermissionTo([
            'patients.view',
            'consultations.view', 'consultations.create', 'consultations.edit',
            'rendezvous.view',
            'prescriptions.create', 'examens.create',
            //'chat_ia.use'
        ]);

        // PATIENT (Portail patient)
        $patient = Role::firstOrCreate(['name' => 'patient']);
        $patient->givePermissionTo([
            'patients.view',
            'rendezvous.view',
            'demandes.create',
            //'chat_ia.use'
        ]);

        // ====================== UTILISATEURS DE TEST ======================

        // Admin par défaut
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@clinique.com'],
            [
                'name' => 'Administrateur Principal',
                'password' => bcrypt('password123'),
                'telephone' => '0700000001',
                'is_active' => true,
            ]
        );
        $adminUser->assignRole('admin');

        // Médecin de test
        $medecinUser = User::firstOrCreate(
            ['email' => 'dr.diallo@clinique.com'],
            [
                'name' => 'Dr. Amadou Diallo',
                'password' => bcrypt('password123'),
                'telephone' => '0700000002',
                'is_active' => true,
            ]
        );
        $medecinUser->assignRole('medecin');

        // Secrétaire de test
        $secretaireUser = User::firstOrCreate(
            ['email' => 'secretaire@clinique.com'],
            [
                'name' => 'Aissatou Secretaire',
                'password' => bcrypt('password123'),
                'telephone' => '0700000003',
                'is_active' => true,
            ]
        );
        $secretaireUser->assignRole('secretaire');

        $this->command->info('✅ Rôles, Permissions et Utilisateurs de test créés avec succès !');
    }
}
