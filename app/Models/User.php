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

    /**
     * Mass assignable attributes.
     */
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

    /**
     * Hidden attributes for arrays.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Cast attributes.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'interests' => 'array',
    ];

    // ───────────────────────────────────────────────
    // RELATIONSHIPS
    // ───────────────────────────────────────────────

    // 💳 Credit Cards
    public function creditCards()
    {
        return $this->hasMany(CreditCard::class);
    }

    // ⚙️ Preferences (1-to-1)
    public function preferences()
    {
        return $this->hasOne(UserPreference::class);
    }

    // 💵 Payments
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // 📦 Subscriptions
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    // 🤝 Networking
    public function connectionsSent()
    {
        return $this->hasMany(Connection::class, 'requester_id');
    }

    public function connectionsReceived()
    {
        return $this->hasMany(Connection::class, 'receiver_id');
    }

    // 💬 Messages
    public function messagesSent()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function messagesReceived()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    // 🧭 Local Touch Bookings
    public function localTouchBookings()
    {
        return $this->hasMany(LocalTouchBooking::class);
    }

    // 💸 Local Touch Payments (via bookings)
    public function localTouchPayments()
    {
        return $this->hasManyThrough(LocalTouchPayment::class, LocalTouchBooking::class);
    }
}
