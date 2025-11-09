<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tour_id',
        'start_date',
        'travelers',
        'customer_notes',
        'unit_price',
        'subtotal',
        'discount',
        'tax',
        'total_amount',
        'currency',
        'status', // pending|confirmed|cancelled|refunded
        'paid',
    ];

    protected $casts = [
        'start_date'     => 'date',
        'travelers'      => 'integer',
        'customer_notes' => 'array',
        'unit_price'     => 'decimal:2',
        'subtotal'       => 'decimal:2',
        'discount'       => 'decimal:2',
        'tax'            => 'decimal:2',
        'total_amount'   => 'decimal:2',
        'paid'           => 'boolean',
    ];

    /* Relationships */
    public function tour()
    {
        return $this->belongsTo(Tour::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payments()
    {
        return $this->hasMany(TourPayment::class);
    }

    /* Scopes */
    public function scopePaid($q)
    {
        return $q->where('paid', true);
    }

    public function scopeMine($q, int $userId)
    {
        return $q->where('user_id', $userId);
    }

    /* Helpers */
    public function markPaid(): void
    {
        $this->forceFill([
            'paid'   => true,
            'status' => 'confirmed',
        ])->save();
    }

    public function markCancelled(): void
    {
        $this->forceFill(['status' => 'cancelled'])->save();
    }
}
