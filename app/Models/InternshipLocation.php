<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternshipLocation extends Model
{
    use HasFactory;
    protected $fillable = [
        'slug', 'country', 'cities', 'flag', 'popular',
    ];

    protected $casts = [
        'cities' => 'array',
        'popular' => 'boolean',
    ];
}
