<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    protected $fillable = ['country', 'city', 'image', 'highlights', 'cuisine'];

    public function pointsOfInterest()
    {
        return $this->hasMany(PointOfInterest::class);
    }

    public function attractions()
    {
        return $this->hasMany(Attraction::class);
    }
}