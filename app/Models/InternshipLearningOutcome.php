<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternshipLearningOutcome extends Model
{
    use HasFactory;

    protected $fillable = [
        'internship_id',
        'outcome',
    ];

    public function internship()
    {
        return $this->belongsTo(Internship::class);
    }
}
