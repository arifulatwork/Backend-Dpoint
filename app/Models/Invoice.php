<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'payment_id',
        'invoice_number',
        'issued_at',
        'billing_details',
    ];

    protected $casts = [
        'billing_details' => 'array',
        'issued_at' => 'date',
    ];

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}

