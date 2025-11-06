<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attraction extends Model
{
    use HasFactory;

    protected $fillable = [
        'destination_id',
        'name',
        'type',
        'duration',
        'price',
        'group_price',
        'min_group_size',
        'max_group_size',
        'image',
        'highlights',
    ];

    /**
     * Attribute casting
     * - highlights: array <-> json column
     * - price / group_price: decimal(,2)
     * - group sizes: integers
     */
    protected $casts = [
        'highlights'      => 'array',
        'price'           => 'decimal:2',
        'group_price'     => 'decimal:2',
        'min_group_size'  => 'integer',
        'max_group_size'  => 'integer',
    ];

    /* -------------------- Relationships -------------------- */

    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }

    public function guide()
    {
        return $this->hasOne(Guide::class);
    }

    public function openingHours()
        {
            return $this->hasMany(AttractionOpeningHour::class)->orderBy('day_of_week');
        }
}
