<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    protected $fillable = [
        'company_id','plan_id','is_trial','start_date','end_date','status',
        'price','last_payment_date','next_billing_date',
    ];

    protected $casts = [
        'is_trial'          => 'boolean',
        'start_date'        => 'date',
        'end_date'          => 'date',
        'last_payment_date' => 'date',
        'next_billing_date' => 'date',
    ];

    public function company(): BelongsTo { return $this->belongsTo(Company::class); }
    public function plan(): BelongsTo { return $this->belongsTo(Plan::class); }

    public function scopeActive($q)
    {
        return $q->where('status','active')
                 ->where(function($qq){
                     $qq->whereNull('end_date')->orWhere('end_date','>', now()->toDateString());
                 });
    }
}
