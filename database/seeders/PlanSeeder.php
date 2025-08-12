<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $make = fn(array $a) => Plan::updateOrCreate(['slug' => $a['slug']], $a);

        $make([
            'name' => 'Starter',
            'slug' => 'starter',
            'description' => 'Comienza ya, sin costo.',
            'price' => 0,
            'max_requests_per_month' => 30,
            'max_departments' => 1,
            'max_users' => 1,
            'custom_colors' => false,
            'custom_banner' => false,
            'enable_sign' => true,
            'enable_qr' => true,
            'storage_gb' => 1,
            'support_channel' => 'tickets_email',
            'allow_custom_domain' => false,
            'is_active' => true,
        ]);

        $make([
            'name' => 'Essential',
            'slug' => 'essential',
            'description' => 'Todo lo básico bien hecho.',
            'price' => 79000,
            'max_requests_per_month' => 1000,
            'max_departments' => 3,
            'max_users' => 5,
            'custom_colors' => true,
            'custom_banner' => false,
            'enable_sign' => true,
            'enable_qr' => true,
            'storage_gb' => 10,
            'support_channel' => 'tickets_email',
            'allow_custom_domain' => false,
            'is_active' => true,
        ]);

        $make([
            'name' => 'Pro',
            'slug' => 'pro',
            'description' => 'Herramientas y capacidad para crecer.',
            'price' => 199000,
            'max_requests_per_month' => 10000,
            'max_departments' => 10,
            'max_users' => 25,
            'custom_colors' => true,
            'custom_banner' => true,
            'enable_sign' => true,
            'enable_qr' => true,
            'storage_gb' => 100,
            'support_channel' => 'tickets_email',
            'allow_custom_domain' => true,
            'is_active' => true,
        ]);

        $make([
            'name' => 'Enterprise',
            'slug' => 'enterprise',
            'description' => 'Ilimitado + soporte WhatsApp.',
            'price' => 0, // a la medida / cotización
            'max_requests_per_month' => 0,  // 0 = ilimitado
            'max_departments' => 0,
            'max_users' => 0,
            'custom_colors' => true,
            'custom_banner' => true,
            'enable_sign' => true,
            'enable_qr' => true,
            'storage_gb' => null, // a medida
            'support_channel' => 'whatsapp',
            'allow_custom_domain' => true,
            'is_active' => true,
        ]);
    }
}

