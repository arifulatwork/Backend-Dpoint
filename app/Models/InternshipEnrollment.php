<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternshipEnrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'internship_id',
        'stripe_payment_intent_id',
        'stripe_customer_id',
        'amount',
        'currency',
        'status',
        'payment_details',
        'enrolled_at',
        'payment_completed_at',
        'failure_message',
    ];

    protected $casts = [
        'payment_details' => 'array',
        'enrolled_at' => 'datetime',
        'payment_completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function internship()
    {
        return $this->belongsTo(Internship::class);
    }

    public function payments()
    {
        return $this->hasMany(InternshipPayment::class, 'enrollment_id');
    }
}
