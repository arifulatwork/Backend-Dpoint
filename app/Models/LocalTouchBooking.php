<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocalTouchBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'experience_id',
        'date',
        'time',
        'participants',
        'special_requests',
        'status',
    ];

    protected $casts = [
        'date' => 'date',
        'time' => 'string', // Optional: if you store time as string like "14:30"
        'participants' => 'integer',
        'special_requests' => 'string',
    ];

    /**
     * The user who made the booking.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The experience being booked.
     */
    public function experience()
    {
        return $this->belongsTo(Experience::class);
    }

    /**
     * The payment associated with this booking.
     */
    public function payment()
    {
        return $this->hasOne(LocalTouchPayment::class);
    }
}
