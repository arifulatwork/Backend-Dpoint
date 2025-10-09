<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Auth\Notifications\ResetPassword;

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
     * Attribute casting.
     * - 'password' => 'hashed' auto-hashes on set (Laravel 10+)
     * - 'interests' => 'array' keeps JSON as PHP array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'interests'         => 'array',
        'password'          => 'hashed',
    ];

    /**
     * Default attribute values.
     */
    protected $attributes = [
        'interests' => '[]',
    ];

    // ───────────────────────────────────────────────
    // PASSWORD RESET (SPA-FRIENDLY URL)
    // ───────────────────────────────────────────────

    /**
     * Override the default password reset notification to point to your SPA route.
     * It builds a URL like:
     *   {FRONTEND_URL}/reset-password?token=...&email=...
     *
     * Set FRONTEND_URL in .env (fallbacks to APP_URL).
     */
    public function sendPasswordResetNotification($token): void
    {
        $frontend = config('app.frontend_url', env('FRONTEND_URL', config('app.url', 'http://localhost')));

        $resetUrl = rtrim($frontend, '/')
            . '/reset-password?token=' . $token
            . '&email=' . urlencode($this->email);

        // Use an anonymous class extending the default to force our SPA URL in the button
        $this->notify(new class($token, $resetUrl) extends ResetPassword {
            protected string $spaUrl;

            public function __construct(string $token, string $spaUrl)
            {
                parent::__construct($token);
                $this->spaUrl = $spaUrl;
            }

            public function toMail($notifiable)
            {
                $mail = parent::toMail($notifiable);
                return $mail->action(__('Reset Password'), $this->spaUrl);
            }
        });
    }

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
