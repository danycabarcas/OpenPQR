<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Limpiar cache de permisos
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define los permisos del sistema (agrega/quita según módulos)
        $permissions = [
            // Planes
            'ver planes',
            'crear planes',
            'editar planes',
            'eliminar planes',

            // Empresas
            'ver empresas',
            'crear empresas',
            'editar empresas',
            'eliminar empresas',

            // Usuarios
            'ver usuarios',
            'crear usuarios',
            'editar usuarios',
            'eliminar usuarios',

            // Departamentos
            'ver departamentos',
            'crear departamentos',
            'editar departamentos',
            'eliminar departamentos',

            // Solicitudes PQRSD
            'ver solicitudes',
            'crear solicitudes',
            'responder solicitudes',
            'asignar solicitudes',
            'eliminar solicitudes',

            // Facturación y pagos
            'ver facturacion',
            'gestionar facturacion',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // Crea los roles principales
        $roles = [
            'super_admin',
            'company_admin',
            'supervisor',
            'agent',
            'viewer',
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // Asigna permisos a cada rol

        // Super Admin: todos los permisos
        $superAdmin = Role::findByName('super_admin');
        $superAdmin->syncPermissions(Permission::all());

        // Admin de empresa
        $companyAdmin = Role::findByName('company_admin');
        $companyAdmin->syncPermissions([
            'ver empresas',
            'editar empresas',

            'ver usuarios',
            'crear usuarios',
            'editar usuarios',
            'eliminar usuarios',

            'ver departamentos',
            'crear departamentos',
            'editar departamentos',
            'eliminar departamentos',

            'ver solicitudes',
            'crear solicitudes',
            'responder solicitudes',
            'asignar solicitudes',

            'ver facturacion',
        ]);

        // Supervisor
        $supervisor = Role::findByName('supervisor');
        $supervisor->syncPermissions([
            'ver usuarios',
            'ver departamentos',
            'ver solicitudes',
            'responder solicitudes',
            'asignar solicitudes',
        ]);

        // Agente (Funcionario)
        $agent = Role::findByName('agent');
        $agent->syncPermissions([
            'ver solicitudes',
            'responder solicitudes',
        ]);

        // Solo consulta (viewer)
        $viewer = Role::findByName('viewer');
        $viewer->syncPermissions([
            'ver solicitudes',
        ]);
    }
}
