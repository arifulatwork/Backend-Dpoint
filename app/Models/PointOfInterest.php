<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PointOfInterest extends Model
{
    protected $fillable = ['destination_id', 'name', 'latitude', 'longitude', 'type'];

    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }
}