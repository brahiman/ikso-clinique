<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Réinitialiser le cache des permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ==================== PERMISSIONS ====================
        $permissions = [
            // Gestion Utilisateurs
            'users.view', 'users.create', 'users.edit', 'users.delete',

            // Patients
            'patients.view', 'patients.create', 'patients.edit', 'patients.delete',

            // Médecins
            'medecins.view', 'medecins.create', 'medecins.edit',

            // Demandes de consultation
            'demandes.view', 'demandes.create', 'demandes.affecter',

            // Rendez-vous
            'rendezvous.view', 'rendezvous.create', 'rendezvous.edit',

            // Consultations & Dossier Médical
            'consultations.view', 'consultations.create', 'consultations.edit',

            // Statistiques
            'stats.view',

            // Chat IA
           // 'chat_ia.use',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // ==================== RÔLES ====================

        // Role Admin
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all()); // Admin a tout

        // Role Secrétaire
        $secretaire = Role::firstOrCreate(['name' => 'secretaire']);
        $secretaire->givePermissionTo([
            'patients.view', 'patients.create', 'patients.edit',
            'demandes.view', 'demandes.create', 'demandes.affecter',
            'rendezvous.view', 'rendezvous.create', 'rendezvous.edit',
            'medecins.view',
            'stats.view'
        ]);

        // Role Médecin
        $medecin = Role::firstOrCreate(['name' => 'medecin']);
        $medecin->givePermissionTo([
            'patients.view',
            'consultations.view', 'consultations.create', 'consultations.edit',
            'rendezvous.view'
        ]);

        // Role Patient (portail patient)
        $patient = Role::firstOrCreate(['name' => 'patient']);
        $patient->givePermissionTo([
            'patients.view',
            'rendezvous.view',
            'demandes.create'
        ]);

        // Création d'un utilisateur Admin par défaut
        $adminUser = \App\Models\User::firstOrCreate(
            ['email' => 'admin@clinique.com'],
            [
                'name' => 'Administrateur Principal',
                'password' => bcrypt('password'),
                'telephone' => '0123456789',
                'is_active' => true,
            ]
        );
        $adminUser->assignRole('admin');

        $this->command->info('Rôles et Permissions créés avec succès !');
    }
}
