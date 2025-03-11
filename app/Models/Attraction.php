<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attraction extends Model
{
    protected $fillable = [
        'destination_id', 'name', 'type', 'duration', 'price', 'group_price', 
        'min_group_size', 'max_group_size', 'image', 'highlights'
    ];

    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }

    public function guide()
    {
        return $this->hasOne(Guide::class);
    }
}