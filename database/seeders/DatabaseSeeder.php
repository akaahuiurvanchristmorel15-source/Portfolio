<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // <-- AJOUTE CETTE LIGNE ICI

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@appAdmin.com'],
            [
                'name'     => 'Administrateur',
                'password' => Hash::make('Admin@1234'), // Maintenant Laravel sait où la trouver !
                'role'     => 'admin',
            ]
        );
    }
}