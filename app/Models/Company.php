<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'name','slug','sector','logo_url','banner_url','color_primary',
        'email_contact','phone_contact','is_active',
    ];

    protected $casts = ['is_active'=>'boolean'];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function activeSubscription()
    {
        return $this->hasOne(Subscription::class)
            ->ofMany(['start_date' => 'max'], function($q) {
                $q->where('status','active')
                  ->where(function($qq){
                      $qq->whereNull('end_date')->orWhere('end_date','>', now()->toDateString());
                  });
            });
    }

    public function currentPlan()
    {
        return $this->activeSubscription?->plan;
    }
}
