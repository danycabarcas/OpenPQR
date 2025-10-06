<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    protected $fillable = ['name','slug','is_dark','variables'];
    protected $casts = ['variables'=>'array','is_dark'=>'boolean'];
}
