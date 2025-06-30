<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttractionBooking extends Model
{
    protected $fillable = [
        'attraction_id', 'user_id', 'participants', 'booking_date', 'status'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function attraction() {
        return $this->belongsTo(Attraction::class);
    }

    public function payment() {
        return $this->hasOne(AttractionPayment::class, 'booking_id');
    }
}

