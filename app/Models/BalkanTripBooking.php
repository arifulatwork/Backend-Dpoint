<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BalkanTripBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'balkan_trip_id',
        'stripe_payment_intent_id',
        'paid',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function balkanTrip()
    {
        return $this->belongsTo(BalkanTrip::class);
    }
}

