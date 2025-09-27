<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TravelPersonaQuestion extends Model
{
    protected $fillable = ['key', 'text', 'multiple', 'has_budget_slider'];

    public function options()
    {
        return $this->hasMany(TravelPersonaOption::class, 'question_id');
    }
}
