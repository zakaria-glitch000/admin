<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Chercher le rôle Admin
        $role = Role::where('name', 'Admin')->first();

        // Créer ou mettre à jour l'utilisateur admin
        $user = User::updateOrCreate(
            [
                'email' => 'admin@gmail.com',
            ],
            [
                'nom' => 'Administrateur',
                'telephone' => '0600000000',
                'password' => Hash::make('123456'),
                'is_active' => true,
            ]
        );

        // Affecter le rôle Admin
        if ($role) {
            $user->assignRole($role);
            $user->role_id = $role->id;
            $user->save();
        }
    }
}