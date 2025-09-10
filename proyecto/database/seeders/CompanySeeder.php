<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;

class CompanySeeder extends Seeder
{
    public function run()
    {
        Company::create([
            'name'           => 'Gobernación del Magdalena',
            'slug'           => 'gobernacion-magdalena',
            'sector'         => 'Entidad Pública',
            'logo_url'       => 'logos/gobmagdalena.png',   // Coloca la ruta que uses
            'banner_url'     => null,                       // Premium si aplica
            'color_primary'  => '#cf003c',                  // Rojo institucional
            'email_contact'  => 'contacto@gobmagdalena.gov.co',
            'phone_contact'  => '3001234567',
            'address'        => 'Carrera 1 # 23-45, Santa Marta',
            'city'           => 'Santa Marta',
            'nit'            => '800123456-7',
            'plan_id'        => 1,         // Puede ser null si prefieres manejar plan por suscripción
            'is_active'      => true,
        ]);

        // Puedes agregar más empresas demo aquí si lo necesitas
    }
}
