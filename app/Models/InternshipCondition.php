<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternshipCondition extends Model
{
    use HasFactory;
    protected $fillable = ['slug', 'text', 'required'];

    protected $casts = [
        'required' => 'boolean',
    ];
}
