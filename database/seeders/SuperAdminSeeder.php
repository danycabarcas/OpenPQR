<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class SuperAdminSeeder extends Seeder
{
    public function run()
    {
        // 1. Crear el rol si no existe
        $role = Role::firstOrCreate(['name' => 'super_admin']);

        // 2. Buscar el usuario por email
        $user = User::where('email', 'admin@demo.com')->first();

        if ($user) {
            // 3. Asignar el rol
            $user->assignRole($role->name);
            echo "Rol super_admin asignado al usuario admin@demo.com\n";
        } else {
            echo "Usuario admin@demo.com no encontrado\n";
        }
    }
}
