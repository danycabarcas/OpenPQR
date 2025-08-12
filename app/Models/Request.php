<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    protected $fillable = [
        'company_id', 'department_id', 'type', 'subject', 'description', 'tracking_code', 'status', 'response_due_date',
        'created_via', 'is_anonymous', 'citizen_type_document', 'citizen_document', 'citizen_name', 'citizen_lastname',
        'citizen_phone', 'citizen_email', 'citizen_address'
    ];

    public function company() {
        return $this->belongsTo(Company::class);
    }

    public function department() {
        return $this->belongsTo(Department::class);
    }
}
