<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = [
        'company_id', 'name', 'email_notify', 'is_active'
    ];

    public function company() {
        return $this->belongsTo(Company::class);
    }

    public function users() {
        return $this->hasMany(User::class);
    }
}
