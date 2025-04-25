<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PremiumTierFeature extends Model
{
    use HasFactory;
    protected $fillable = ['premium_tier_id', 'feature'];
}
