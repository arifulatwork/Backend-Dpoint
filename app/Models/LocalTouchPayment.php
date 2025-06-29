<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocalTouchPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'stripe_payment_intent_id',
        'stripe_payment_method',
        'amount',
        'status',
        'payment_details',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_details' => 'array',
    ];

    /**
     * The booking associated with this payment.
     */
    public function booking()
    {
        return $this->belongsTo(LocalTouchBooking::class);
    }

    /**
     * Get the user through the booking (optional shortcut).
     */
    public function user()
    {
        return $this->hasOneThrough(User::class, LocalTouchBooking::class, 'id', 'id', 'booking_id', 'user_id');
    }

    /**
     * Scope to easily find succeeded payments.
     */
    public function scopeSucceeded($query)
    {
        return $query->where('status', 'succeeded');
    }
}
