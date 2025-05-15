<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PointOfInterest extends Model
{
    use HasFactory;

    protected $table = 'points_of_interest';

    protected $fillable = [
        'destination_id',
        'name',
        'type',
        'latitude',
        'longitude',
        'description',
        'image',
        'rating',
        'price',
        'booking_url',
        'amenities',
        'flight_details',
        'shuttle_details',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'amenities' => 'array',
        'flight_details' => 'array',
        'shuttle_details' => 'array',
    ];

    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }
}
