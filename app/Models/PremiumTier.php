<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PremiumTier extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'price', 'period', 'type', 'is_popular'];

    public function features()
    {
        return $this->hasMany(PremiumTierFeature::class);
    }
}
