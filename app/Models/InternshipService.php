<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternshipService extends Model
{
    use HasFactory;
    protected $fillable = [
        'slug', 'name', 'description', 'price', 'original_price', 'popular',
    ];

    protected $casts = [
        'popular' => 'boolean',
        'price' => 'float',
        'original_price' => 'float',
    ];
}
