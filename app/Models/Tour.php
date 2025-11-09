<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'category',        // e.g. 'montenegro', 'balkan', 'spain'
        'description',
        'duration',        // e.g. '7' or '10'
        'price',           // decimal
        'currency',        // default: EUR
        'image_url',
        'destinations',    // json array
        'group_size',      // json {min,max}
        'itinerary',       // json array
        'included',        // json array
        'not_included',    // json array
        'is_active',
    ];

    protected $casts = [
        'price'         => 'decimal:2',
        'destinations'  => 'array',
        'group_size'    => 'array',
        'itinerary'     => 'array',
        'included'      => 'array',
        'not_included'  => 'array',
        'is_active'     => 'boolean',
    ];

    protected $attributes = [
        'currency'  => 'EUR',
        'is_active' => true,
    ];

    /* ---------------- Relationships ---------------- */
    public function bookings()
    {
        return $this->hasMany(TourBooking::class);
    }

    /* ---------------- Scopes ---------------- */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    /* ---------------- Accessors ---------------- */
    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->price, 2) . ' ' . $this->currency;
    }

    public function getDurationLabelAttribute(): string
    {
        return $this->duration . ' days';
    }
}
