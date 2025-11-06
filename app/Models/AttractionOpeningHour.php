<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttractionOpeningHour extends Model
{
    use HasFactory;

    protected $table = 'attraction_opening_hours';

    protected $fillable = [
        'attraction_id',
        'day_of_week',
        'open_time',
        'close_time',
        'is_closed',
        'timezone',
    ];

    protected $casts = [
        'day_of_week' => 'integer',
        'is_closed'   => 'boolean',
        // times are cast to strings by default; keep as 'datetime:H:i' if needed
    ];

    public function attraction()
    {
        return $this->belongsTo(Attraction::class);
    }

    /** Helper: returns "Mon", "Tuesday", etc. */
    public function getDayNameAttribute(): string
    {
        // 0=Sun ... 6=Sat
        return [
            'Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'
        ][$this->day_of_week] ?? 'Unknown';
    }
} 