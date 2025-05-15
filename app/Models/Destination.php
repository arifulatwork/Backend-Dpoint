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
        'latitude',
        'longitude',
        'visit_type',
        'highlights',
        'cuisine',
    ];

    protected $casts = [
        'highlights' => 'array',
        'cuisine' => 'array',
        'latitude' => 'float',
        'longitude' => 'float',
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
