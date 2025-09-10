<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    public function run()
    {
        Department::create([
            'company_id' => 1,
            'name' => 'Atención al Ciudadano',
            'email_notify' => 'soporte@demo.com',
            'is_active' => true,
        ]);
    }
}
