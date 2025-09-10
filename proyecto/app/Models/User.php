<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles; // Importa el trait de Spatie
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable, HasRoles; // ¡Agrega el trait aquí!

    protected $fillable = [
        'company_id', 'department_id', 'name', 'lastname', 'email', 'password', 'phone', 'is_active'
    ];

    // Oculta el password y remember_token al serializar (opcional, buena práctica)
    protected $hidden = [
        'password', 'remember_token',
    ];

    // Opcional: casts para is_active booleano
    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relación: El usuario pertenece a una empresa
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    // Relación: El usuario pertenece a un departamento
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    // Puedes agregar otras relaciones aquí (por ejemplo solicitudes, etc)
}
