<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternshipApplication extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'company_id',
        'start_date', 'end_date', 'duration_months',
        'selected_services', 'accepted_conditions',
        'total_price', 'currency',
        'cv_path', 'status',
        'stripe_payment_intent_id',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'selected_services' => 'array',
        'accepted_conditions' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(InternshipCompany::class, 'company_id');
    }
}
