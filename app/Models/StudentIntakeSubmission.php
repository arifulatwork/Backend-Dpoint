<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class StudentIntakeSubmission extends Model
{
    protected $fillable = [
        'user_id',
        'full_name',
        'email',
        'contact_phone',
        'nationality',
        
        // NEW: Current location field
        'current_location',
        
        // REMOVED: Target country and current situation
        // 'target_country',
        // 'current_situation',
        
        // UPDATED: Visa status fields
        'visa_status',
        'visa_expiry_date',
        
        // UPDATED: Residence document field
        'has_residence_card',
        
        // NEW: Student status field
        'student_status',
        
        // NEW: Accommodation and insurance fields
        'has_accommodation',
        'has_health_insurance',
        'has_empadronamiento',
        
        // UPDATED: Services needed
        'services_needed',
        
        // UPDATED: Renamed from professional_info
        'additional_info',
        
        // REMOVED: Future plans field
        // 'future_plans',
        
        'document_paths',
        'amount_cents',
        'currency',
        'status',
        'stripe_payment_intent_id',
        'submitted_at'
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