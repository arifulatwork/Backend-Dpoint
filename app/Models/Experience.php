<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'name',
        'description',
        'price',
        'rating',
        'reviews',
        'location',
        'duration',
        'max_participants',
        'image',
        'city',
        'host'
    ];

    protected $casts = [
        'host' => 'array',
        'price' => 'decimal:2',
        'rating' => 'float',
        'reviews' => 'integer',
        'max_participants' => 'integer'
    ];

    // Optionally add accessors/mutators for the host field
    public function setHostAttribute($value)
    {
        $this->attributes['host'] = json_encode($value);
    }

    public function getHostAttribute($value)
    {
        return json_decode($value, true);
    }

    // Add scopes for filtering
    public function scopeFood($query)
    {
        return $query->where('type', 'food');
    }

    public function scopeMusic($query)
    {
        return $query->where('type', 'music');
    }

    public function scopeCraft($query)
    {
        return $query->where('type', 'craft');
    }
}