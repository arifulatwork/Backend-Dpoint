<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternshipPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'enrollment_id',
        'stripe_payment_intent_id',
        'amount',
        'currency',
        'status',
        'stripe_response',
    ];

    protected $casts = [
        'stripe_response' => 'array',
    ];

    public function enrollment()
    {
        return $this->belongsTo(InternshipEnrollment::class, 'enrollment_id');
    }
}
