<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourPayment extends Model
{
    use HasFactory;

    protected $table = 'tour_payments';

    protected $fillable = [
        'tour_booking_id',
        'provider',              // 'stripe', 'paypal', etc.
        'amount',
        'currency',
        'provider_intent_id',    // Stripe PI, etc.
        'provider_payment_id',   // Stripe charge / PayPal capture id
        'status',                // processing|succeeded|failed|...
        'refunded_amount',
        'receipt_url',
        'provider_payload',      // raw webhook snapshot
    ];

    protected $casts = [
        'amount'           => 'decimal:2',
        'refunded_amount'  => 'decimal:2',
        'provider_payload' => 'array',
    ];

    /* Relationships */
    public function booking()
    {
        return $this->belongsTo(TourBooking::class, 'tour_booking_id');
    }
}
