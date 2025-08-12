<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'sector',
        'logo_url',
        'banner_url',
        'color_primary',
        'email_contact',
        'phone_contact',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Company $company) {
            if (empty($company->slug)) {
                $company->slug = Str::slug($company->name);
            }
        });

        static::updating(function (Company $company) {
            if (empty($company->slug)) {
                $company->slug = Str::slug($company->name);
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Suscripción activa (la más reciente con estado 'active').
     * Si prefieres 100% compatibilidad, cambia por hasMany()->where()->latest()->first()
     */
    public function activeSubscription()
    {
        return $this->hasOne(Subscription::class)
            ->latestOfMany()
            ->where('status', 'active');
    }

    /**
     * Accesor útil: $company->active_plan_id
     */
    public function getActivePlanIdAttribute()
    {
        $sub = $this->activeSubscription; // relación hasOne (puede ser null)
        return $sub ? $sub->plan_id : null;
    }
}
