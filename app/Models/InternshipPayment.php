<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternshipPayment extends Model
{
    use HasFactory;

    // If you want to be explicit:
    protected $fillable = [
        'enrollment_id',
        'stripe_payment_intent_id',
        'stripe_checkout_session_id',
        'amount',
        'currency',
        'status',
        'stripe_response',
    ];

    // Or just allow everything except guarded:
    // protected $guarded = [];

    protected $casts = [
        'stripe_response' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ─────────────── RELATIONSHIPS ───────────────
    public function enrollment()
    {
        return $this->belongsTo(InternshipEnrollment::class, 'enrollment_id');
    }
}
