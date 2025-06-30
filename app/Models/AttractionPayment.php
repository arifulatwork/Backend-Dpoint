<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttractionPayment extends Model
{
    protected $fillable = [
        'booking_id', 'payment_intent_id', 'payment_method', 'amount', 'currency', 'status'
    ];

    public function booking() {
        return $this->belongsTo(AttractionBooking::class, 'booking_id');
    }
}
