<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecialDiscount extends Model
{
    use HasFactory;
    protected $fillable = [
        'title', 'description', 'location', 'discount', 
        'category', 'valid_until', 'image'
    ];
}
