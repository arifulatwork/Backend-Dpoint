<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RealEstateProperty extends Model
{
    use HasFactory;
    protected $fillable = [
        'title', 'description', 'location', 'price', 'type',
        'bedrooms', 'bathrooms', 'area', 'image', 'premium_discount'
    ];
}
