<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternshipCompany extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'logo_url', 'location', 'field_slug',
        'rating', 'reviews', 'work_mode', 'duration', 'hours',
    ];
}
