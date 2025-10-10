<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class StudentIntakeSubmission extends Model
{
    protected $fillable = [
        'user_id','full_name','email','contact_phone','nationality','target_country',
        'current_situation','visa_expiry_date','has_residence_card','services_needed',
        'professional_info','future_plans','document_paths','amount_cents','currency',
        'status','stripe_payment_intent_id','submitted_at'
    ];

    protected $casts = [
        'services_needed' => 'array',
        'document_paths'  => 'array',
        'submitted_at'    => 'datetime',
        'visa_expiry_date'=> 'date',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
