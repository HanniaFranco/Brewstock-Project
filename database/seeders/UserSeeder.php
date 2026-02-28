<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener el rol de Administrador
        $adminRole = Role::where('name', 'Administrador')->first();

        // Crear usuarios de prueba
        $users = [
            [
                'name' => 'Eduardo Rodríguez',
                'email' => 'eduardo.rdzar@hotmail.com',
                'password' => Hash::make('password123'),
                'role_id' => $adminRole?->id,
            ],
            [
                'name' => 'Admin User',
                'email' => 'admin@brewstock.com',
                'password' => Hash::make('admin123'),
                'role_id' => $adminRole?->id,
            ],
            [
                'name' => 'Gerente de Ventas',
                'email' => 'gerente@brewstock.com',
                'password' => Hash::make('password123'),
                'role_id' => Role::where('name', 'Gerente')->first()?->id,
            ],
        ];

        foreach ($users as $user) {
            User::firstOrCreate(
                ['email' => $user['email']],
                $user
            );
        }
    }
}
