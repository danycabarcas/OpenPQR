<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// Si quieres SoftDeletes, descomenta la línea de abajo y agrega la columna deleted_at en la migración:
// use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Company extends Model
{
    use HasFactory;
    // use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'sector',
        'email_contact',
        'phone_contact',
        'color_primary',
        'logo_url',
        'banner_url',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Si quieres que siempre puedas leer company.current_plan_id
    protected $appends = ['current_plan_id'];

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function activeSubscription()
    {
        // Si manejas status en subscriptions:
        return $this->hasOne(Subscription::class)
            ->where('status', 'active')
            ->latestOfMany();

        // Si NO manejas status y siempre la última es la vigente:
        // return $this->hasOne(Subscription::class)->latestOfMany();
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors / Mutators
    |--------------------------------------------------------------------------
    */

    // ID del plan activo, cómodo para blades: {{ $company->current_plan_id }}
    public function getCurrentPlanIdAttribute()
    {
        return $this->activeSubscription?->plan_id;
    }

    // Si alguna vez guardas rutas relativas en DB, estos accessors devuelven URL completa.
    // OJO: el controller ya guarda Storage::url(), así que esto es “por si acaso”.
    protected function logoUrl(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if (!$value) return null;
                return str_starts_with($value, 'http') ? $value : Storage::url($value);
            }
        );
    }

    protected function bannerUrl(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if (!$value) return null;
                return str_starts_with($value, 'http') ? $value : Storage::url($value);
            }
        );
    }

    // Normaliza color primario (añade # si falta)
    protected function colorPrimary(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => $value ? (str_starts_with($value, '#') ? $value : "#{$value}") : null
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeSearch($query, ?string $term)
    {
        $term = trim((string) $term);
        if ($term === '') return $query;

        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
              ->orWhere('slug', 'like', "%{$term}%")
              ->orWhere('email_contact', 'like', "%{$term}%");
        });
    }

    public function scopeOfPlan($query, $planId)
    {
        return $query->whereHas('activeSubscription', fn ($s) => $s->where('plan_id', $planId));
    }

    /*
    |--------------------------------------------------------------------------
    | Eventos del modelo (slug único, etc.)
    |--------------------------------------------------------------------------
    */
    protected static function booted()
    {
        static::creating(function (Company $company) {
            if (empty($company->slug)) {
                $company->slug = static::generateUniqueSlug($company->name);
            } else {
                $company->slug = static::generateUniqueSlug($company->slug, true);
            }
        });

        static::updating(function (Company $company) {
            if (empty($company->slug)) {
                $company->slug = static::generateUniqueSlug($company->name);
            } else {
                $company->slug = static::generateUniqueSlug($company->slug, true, $company->id);
            }
        });

        // Si usas SoftDeletes y quieres borrar archivos al eliminar definitivamente:
        // static::deleting(function (Company $company) {
        //     if ($company->isForceDeleting()) {
        //         $disk = 'public';
        //         foreach (['logo_url','banner_url'] as $col) {
        //             if ($company->{$col}) {
        //                 $old = str_replace('/storage/', '', $company->{$col});
        //                 Storage::disk($disk)->delete($old);
        //             }
        //         }
        //     }
        // });
    }

    private static function generateUniqueSlug(string $source, bool $isSlugAlready = false, ?int $ignoreId = null): string
    {
        $base = $isSlugAlready ? Str::slug($source) : Str::slug($source);
        $slug = $base;
        $i = 1;

        while (
            static::where('slug', $slug)
                ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }
}
