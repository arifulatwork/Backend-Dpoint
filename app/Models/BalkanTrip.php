<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BalkanTrip extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'duration',
        'price',
        'image_url',
        'destinations',
        'group_size',
        'itinerary',
        'included',
        'not_included',
    ];

    protected $casts = [
        'destinations' => 'array',
        'group_size' => 'array',
        'itinerary' => 'array',
        'included' => 'array',
        'not_included' => 'array',
    ];
}
