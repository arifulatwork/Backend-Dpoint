<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternshipCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'icon',
    ];

    public function internships()
    {
    return $this->hasMany(Internship::class, 'category_id');
    }
}
