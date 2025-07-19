<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MontenegroTourBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'montenegro_tour_id',
        'stripe_payment_intent_id',
        'paid',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function montenegroTour()
    {
        return $this->belongsTo(MontenegroTour::class);
    }
}
