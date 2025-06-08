<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'user_id',
        'premium_tier_id',
        'gateway_subscription_id',
        'status',
        'started_at',
        'expires_at',
    ];

    protected $dates = ['started_at', 'expires_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function premiumTier()
    {
        return $this->belongsTo(PremiumTier::class);
    }
}
