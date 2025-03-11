<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guide extends Model
{
    protected $fillable = [
        'attraction_id', 'name', 'avatar', 'rating', 'reviews', 'experience', 'languages'
    ];

    public function attraction()
    {
        return $this->belongsTo(Attraction::class);
    }
}