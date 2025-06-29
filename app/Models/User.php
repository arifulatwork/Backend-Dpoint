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
        'avatar_url',
        'location',
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

    public function payments()
    {
    return $this->hasMany(Payment::class);
    }

    public function subscriptions()
    {
    return $this->hasMany(Subscription::class);
    }



    // Netowork and chat

    // Connections
        public function connectionsSent()
        {
            return $this->hasMany(Connection::class, 'requester_id');
        }

        public function connectionsReceived()
        {
            return $this->hasMany(Connection::class, 'receiver_id');
        }

        // Messages
        public function messagesSent()
        {
            return $this->hasMany(Message::class, 'sender_id');
        }

        public function messagesReceived()
        {
            return $this->hasMany(Message::class, 'receiver_id');
        }


        /**
         * A user can make many local touch bookings.
         */
        public function localTouchBookings()
        {
            return $this->hasMany(LocalTouchBooking::class);
        }

        /**
         * A user can have many local touch payments (via bookings).
         * This is optional if you're accessing payment through bookings.
         */
        public function localTouchPayments()
        {
            return $this->hasManyThrough(LocalTouchPayment::class, LocalTouchBooking::class);
        }



    }