<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'travel_persona',
    ];

    protected $casts = [
        'travel_persona' => 'array',
    ];

    /**
     * Get the user who owns these preferences.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
