<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Internship extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'description',
        'duration',
        'price',
        'original_price',
        'rating',
        'review_count',
        'company',
        'location',
        'mode',        // 'remote' | 'on-site' | 'hybrid'
        'image',
        'featured',
        'deadline',
        'spots_left',
    ];

    protected $casts = [
        'featured'      => 'boolean',
        'deadline'      => 'date',
        'price'         => 'decimal:2',
        'original_price'=> 'decimal:2',
        'rating'        => 'decimal:1',
    ];

    /** Relations */
    public function category()
    {
        return $this->belongsTo(InternshipCategory::class, 'category_id');
    }

    public function skills()
    {
        return $this->belongsToMany(InternshipSkill::class, 'internship_internship_skill', 'internship_id', 'skill_id');
    }

    public function learningOutcomes()
    {
        return $this->hasMany(InternshipLearningOutcome::class);
    }

    /** Useful scopes for filters */
    public function scopeCategoryIn($query, array $categoryIds)
    {
        return $query->when(!empty($categoryIds), fn($q) => $q->whereIn('category_id', $categoryIds));
    }

    public function scopePriceBetween($query, $min, $max)
    {
        return $query->whereBetween('price', [$min, $max]);
    }

    public function scopeLocations($query, array $locations)
    {
        return $query->when(!empty($locations), fn($q) => $q->whereIn('location', $locations));
    }

    public function scopeModes($query, array $modes)
    {
        // Expecting values like ['remote','on-site','hybrid']
        $normalized = array_map(fn($m) => strtolower($m), $modes);
        return $query->when(!empty($normalized), fn($q) => $q->whereIn('mode', $normalized));
    }

    public function scopeHasSkills($query, array $skills)
    {
        return $query->when(!empty($skills), function ($q) use ($skills) {
            $q->whereHas('skills', function ($sq) use ($skills) {
                $sq->whereIn('name', $skills);
            });
        });
    }
}
