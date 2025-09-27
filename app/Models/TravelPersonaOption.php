<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TravelPersonaOption extends Model
{
    protected $fillable = ['question_id', 'value', 'label', 'description', 'emoji', 'icon'];

    public function question()
    {
        return $this->belongsTo(TravelPersonaQuestion::class, 'question_id');
    }
}
