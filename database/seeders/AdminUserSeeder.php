<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear o actualizar el rol de administrador
        $adminRole = Role::firstOrCreate([
            'name' => 'admin'
        ]);

        // Crear o actualizar el usuario administrador
        User::updateOrCreate([
            'email' => 'ediel.olivero0710@gmail.com'
        ], [
            'name' => 'Ediel Olivero',
            'password' => Hash::make('admin1234'),
            'role_id' => $adminRole->id,
        ]);

        $this->command->info('Usuario administrador creado exitosamente');
        $this->command->info('Email: ediel.olivero0710@gmail.com');
        $this->command->info('Password: admin1234');
    }
}
