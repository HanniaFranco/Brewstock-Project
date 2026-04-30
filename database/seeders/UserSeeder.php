<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->firstOrFail();

        User::firstOrCreate(
            ['email' => 'admin@brewstock.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('Admin1234!'),
                'role_id' => $adminRole->id,
            ]
        );
    }
}
