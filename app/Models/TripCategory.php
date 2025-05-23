<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'icon', 'description'];

    public function trips()
    {
        return $this->hasMany(Trip::class, 'category_id');
    }
}