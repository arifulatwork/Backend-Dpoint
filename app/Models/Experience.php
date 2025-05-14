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
        'host',
        'highlights', // Added
        'why_choose', // Added
    ];

    protected $casts = [
        'host' => 'array',
        'highlights' => 'array', // Added
        'why_choose' => 'array', // Added
        'price' => 'decimal:2',
        'rating' => 'float',
        'reviews' => 'integer',
        'max_participants' => 'integer',
    ];

    // Accessor and Mutator for host (optional but clean)
    public function setHostAttribute($value)
    {
        $this->attributes['host'] = json_encode($value);
    }

    public function getHostAttribute($value)
    {
        return json_decode($value, true);
    }

    // Accessor and Mutator for highlights (optional but clean)
    public function setHighlightsAttribute($value)
    {
        $this->attributes['highlights'] = json_encode($value);
    }

    public function getHighlightsAttribute($value)
    {
        return json_decode($value, true);
    }

    // Accessor and Mutator for why_choose (optional but clean)
    public function setWhyChooseAttribute($value)
    {
        $this->attributes['why_choose'] = json_encode($value);
    }

    public function getWhyChooseAttribute($value)
    {
        return json_decode($value, true);
    }

    // Scopes for filtering by type
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
