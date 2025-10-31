<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PointOfInterest extends Model
{
    use HasFactory;

    protected $table = 'points_of_interest';

    protected $fillable = [
        'destination_id',
        'name',
        'type',
        'position',
        'description',
        'image',
        'rating',
        'price',
        'booking_url',
        'amenities',
        // 'flight_details', // ⛔ not used now (flights removed)
        'shuttle_details',
    ];

    protected $casts = [
        'position'         => 'array',
        'amenities'        => 'array',
        // 'flight_details' => 'array', // ⛔ not used now
        'shuttle_details'  => 'array',
        'rating'           => 'float',
        // Generated columns (from migration) — cast for convenience:
        'latitude'         => 'float',
        'longitude'        => 'float',
    ];

    /**
     * Normalize `position` so it always saves as a JSON array of [float, float].
     * Accepts:
     * - array [lat, lng]
     * - assoc array ['lat' => x, 'lng' => y] / ['latitude'=>x,'longitude'=>y]
     * - string JSON like "[56.50541,21.01943]" or '{"lat":56.5,"lng":21.0}'
     */
    public function setPositionAttribute($value): void
    {
        // Decode JSON string if needed
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $value = $decoded;
            }
        }

        // Assoc -> numeric pair
        if (is_array($value) && isset($value['lat']) && isset($value['lng'])) {
            $value = [(float) $value['lat'], (float) $value['lng']];
        } elseif (is_array($value) && isset($value['latitude']) && isset($value['longitude'])) {
            $value = [(float) $value['latitude'], (float) $value['longitude']];
        }

        // Ensure simple [lat, lng] floats
        if (is_array($value) && count($value) === 2) {
            $lat = is_numeric($value[0]) ? (float) $value[0] : null;
            $lng = is_numeric($value[1]) ? (float) $value[1] : null;

            if (!is_null($lat) && !is_null($lng)) {
                $this->attributes['position'] = json_encode([$lat, $lng]);
                return;
            }
        }

        // Fallback: store null if invalid (prevents bad JSON)
        $this->attributes['position'] = json_encode(null);
    }

    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }

    public function accommodationAppointments()
{
    return $this->hasMany(\App\Models\AccommodationAppointment::class);
}

}
