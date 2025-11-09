<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TourCategory extends Model
{
    protected $fillable = [
        'key','title','description','image',
        'duration_min','duration_max','price_min','price_max',
        'destinations','is_active','sort_order',
    ];

    protected $casts = [
        'destinations' => 'array',
        'is_active' => 'boolean',
    ];

    public function tours()
    {
        // tours.category (string) references tour_categories.key (string)
        return $this->hasMany(Tour::class, 'category', 'key');
    }
}
