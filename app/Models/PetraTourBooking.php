<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PetraTourBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'petra_tour_id',
        'stripe_payment_intent_id',
        'paid',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function petraTour()
    {
        return $this->belongsTo(PetraTour::class);
    }
}
