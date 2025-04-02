<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'title', 'slug', 'description', 'price', 'original_price',
        'discount_percentage', 'image_url', 'duration_days', 'max_participants',
        'highlights', 'learning_outcomes', 'personal_development', 'certifications',
        'environmental_impact', 'community_benefits'
    ];

    protected $casts = [
        'highlights' => 'array',
        'learning_outcomes' => 'array',
        'personal_development' => 'array',
        'certifications' => 'array',
        'environmental_impact' => 'array',
        'community_benefits' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(TripCategory::class, 'category_id');
    }
}