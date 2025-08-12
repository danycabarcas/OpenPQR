<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'company_id' => 1,
            'department_id' => 1,
            'name' => 'Admin',
            'lastname' => 'Principal',
            'email' => 'admin@demo.com',
            'password' => Hash::make('admin12345'), // Cambia la clave luego por seguridad
            'phone' => '3001234567',
            'is_active' => true,
        ]);
    }
}
