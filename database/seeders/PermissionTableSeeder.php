<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionTableSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cache
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Permissions (Zdt hna dyal Devis w Facture)
        $permissions = [
            'user-list',
            'user-create',
            'user-edit',
            'user-delete',

            'role-list',
            'role-create',
            'role-edit',
            'role-delete',

            'ticket-list',
            'ticket-create',
            'ticket-edit',
            'ticket-delete',

            'client-ticket-list',
            'client-ticket-create',
            'client-ticket-show',

            'client-list',
            'client-create',
            'client-edit',
            'client-delete',

            'machine-list',
            'machine-create',
            'machine-edit',
            'machine-delete',

            // 🌟 Devis Permissions
            'devis-list',
            'devis-create',
            'devis-edit',
            'devis-delete',

            // 🌟 Facture Permissions
            'facture-list',
            'facture-create',
            'facture-edit',
            'facture-delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        // Admin Role (Kay-akhod kolchi including Devis w Facture)
        $adminRole = Role::firstOrCreate([
            'name' => 'Admin',
            'guard_name' => 'web',
        ]);

        $adminRole->syncPermissions(Permission::all());

        // Client Role
        $clientRole = Role::firstOrCreate([
            'name' => 'client',
            'guard_name' => 'web',
        ]);

        $clientRole->syncPermissions([
            'client-ticket-list',
            'client-ticket-create',
            'client-ticket-show',
        ]);

        // Admin User
        $user = User::updateOrCreate(
            [
                'email' => 'admin@gmail.com',
            ],
            [
                'nom'        => 'Administrateur',
                'telephone'  => '0600000000',
                'password'   => Hash::make('123456'),
                'is_active'  => true,
            ]
        );

        // Role + Permissions
        $user->syncRoles([$adminRole]);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}