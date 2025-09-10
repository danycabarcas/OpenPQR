<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'name','slug','description','price',
        'max_departments','max_users','max_requests_per_month',
        'custom_colors','custom_banner','enable_sign','enable_qr',
        'storage_gb','support_channel','allow_custom_domain','is_active',
    ];

    protected $casts = [
        'price' => 'integer', // si guardas en COP sin decimales
        'max_departments' => 'integer',
        'max_users' => 'integer',
        'max_requests_per_month' => 'integer',
        'custom_colors' => 'boolean',
        'custom_banner' => 'boolean',
        'enable_sign' => 'boolean',
        'enable_qr' => 'boolean',
        'storage_gb' => 'integer',
        'allow_custom_domain' => 'boolean',
        'is_active' => 'boolean',
    ];
}
