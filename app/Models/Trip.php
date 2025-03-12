<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'type',
        'duration',
        'price',
        'original_price',
        'discount_percentage',
        'image',
        'start_time',
        'end_time',
        'highlights',
        'included',
        'meeting_point',
        'max_participants',
        'special_offer',
    ];

    protected $casts = [
        'highlights' => 'array',
        'included' => 'array',
        'special_offer' => 'array',
    ];
}
