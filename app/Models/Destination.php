<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Destination extends Model
{
    use HasFactory;

    protected $fillable = [
        'country',
        'city',
        'image',
        'coordinates',
        'latitude',
        'longitude',
        'visit_type',
        'highlights',
        'cuisine',
        'description',
        'max_price',
    ];

    protected $casts = [
        'coordinates' => 'array',
        'highlights' => 'array',
        'cuisine' => 'array',
        'latitude' => 'float',
        'longitude' => 'float',
        'max_price' => 'float',
    ];

    public function pointsOfInterest()
    {
        return $this->hasMany(PointOfInterest::class);
    }

    public function attractions()
    {
        return $this->hasMany(Attraction::class);
    }
}
