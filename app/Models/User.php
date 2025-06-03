<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'age',
        'interests',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'interests' => 'array',
    ];

     /**
     * A user can have many credit cards.
     */
    public function creditCards()
    {
        return $this->hasMany(CreditCard::class);
    }

    /**
     * A user can have one set of preferences.
     * (optional, only if you're using a separate table)
     */
    public function preferences()
    {
        return $this->hasOne(UserPreference::class);
    }
}