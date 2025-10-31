<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccommodationAppointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'point_of_interest_id',
        'user_id',
        'appointment_date',
        'end_date',
        'number_of_guests',
        'special_requests',
        'appointment_details',
        'status',
    ];

    protected $casts = [
        'appointment_date'   => 'date',
        'end_date'           => 'date',
        'number_of_guests'   => 'integer',
        'appointment_details'=> 'array',
    ];

    public function pointOfInterest()
    {
        return $this->belongsTo(PointOfInterest::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
