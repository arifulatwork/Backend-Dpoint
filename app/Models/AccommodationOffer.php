<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccommodationOffer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'discount',
        'price',
        'original_price',
        'image',
        'valid_until',
        'description',
        'perks',
    ];

    protected $casts = [
        'perks' => 'array',
    ];
}