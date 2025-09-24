<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternshipSkill extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function internships()
    {
        return $this->belongsToMany(Internship::class, 'internship_internship_skill', 'skill_id', 'internship_id');
    }
}
