<?php

namespace App\Http\Controllers;

use App\Models\StudentIntakeSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StudentIntakeController extends Controller
{
    public function initiate(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'fullName' => 'required|string|max:255',
            'email' => 'required|email',
            'contactPhone' => 'nullable|string|max:50',
            'nationality' => 'required|string|max:100',
            
            // NEW: Current location field
            'currentLocation' => 'required|string|max:100',
            
            // REMOVED: Target country and current situation
            // 'targetCountry' => 'nullable|string|max:100',
            // 'currentSituation' => 'nullable|string|max:100',
            
            // UPDATED: Visa status fields
            'visaStatus' => 'required|string|max:50',
            'visaExpiryDate' => 'nullable|date',
            
            // UPDATED: Residence document field
            'hasResidenceCard' => 'required|string|max:50',
            
            // NEW: Student status field
            'studentStatus' => 'required|string|max:100',
            
            // NEW: Accommodation and insurance fields
            'hasAccommodation' => 'nullable|string|max:20',
            'hasHealthInsurance' => 'nullable|string|max:20',
            'hasEmpadronamiento' => 'nullable|string|max:20',
            
            // UPDATED: Services needed
            'services_needed' => 'nullable',
            
            // UPDATED: Renamed from professionalInfo
            'additionalInfo' => 'nullable|string',
            
            // REMOVED: Future plans field
            // 'futurePlans' => 'nullable|string|max:100',
            
            'documents.*' => 'file|max:5120|mimes:pdf,jpg,jpeg,png',
        ]);

        // Normalize front-end naming
        $servicesNeeded = $request->has('services_needed')
            ? json_decode($request->input('services_needed', '[]'), true)
            : [];

        // Price calculation (example): base + per service addon after the first
        $base = (int) env('INTAKE_BASE_PRICE_CENTS', 9000); // €90.00 in cents
        $addon = (int) env('INTAKE_SERVICE_ADDON_CENTS', 0); // No addon for now, just €90 flat
        $count = is_array($servicesNeeded) ? count($servicesNeeded) : 0;
        $amountCents = $base + max(0, $count - 1) * $addon;

        // Save uploaded files to a temp folder tied to submission (pre-PAYMENT)
        $tmpFolder = 'student_intake/tmp/' . Str::uuid()->toString();
        $paths = [];
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                $paths[] = $file->store($tmpFolder, 'public');
            }
        }

        $submission = StudentIntakeSubmission::create([
            'user_id' => $user->id,
            'full_name' => $validated['fullName'],
            'email' => $validated['email'],
            'contact_phone' => $validated['contactPhone'] ?? null,
            'nationality' => $validated['nationality'],
            
            // NEW: Current location
            'current_location' => $validated['currentLocation'],
            
            // UPDATED: Visa status fields
            'visa_status' => $validated['visaStatus'],
            'visa_expiry_date' => $validated['visaExpiryDate'] ?? null,
            
            // UPDATED: Residence document field
            'has_residence_card' => $validated['hasResidenceCard'],
            
            // NEW: Student status field
            'student_status' => $validated['studentStatus'],
            
            // NEW: Accommodation and insurance fields
            'has_accommodation' => $validated['hasAccommodation'] ?? null,
            'has_health_insurance' => $validated['hasHealthInsurance'] ?? null,
            'has_empadronamiento' => $validated['hasEmpadronamiento'] ?? null,
            
            // UPDATED: Services needed
            'services_needed' => $servicesNeeded,
            
            // UPDATED: Renamed from professional_info
            'additional_info' => $validated['additionalInfo'] ?? null,
            
            // REMOVED: Target country, current situation, future plans
            // 'target_country' => $validated['targetCountry'] ?? null,
            // 'current_situation' => $validated['currentSituation'] ?? null,
            // 'future_plans' => $validated['futurePlans'] ?? null,
            
            'document_paths' => $paths,
            'amount_cents' => $amountCents,
            'currency' => config('services.stripe.currency', 'eur'),
            'status' => 'pending_payment',
        ]);

        // Create PaymentIntent
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        $pi = \Stripe\PaymentIntent::create([
            'amount' => $submission->amount_cents,
            'currency' => $submission->currency,
            'metadata' => [
                'submission_id' => (string) $submission->id,
                'user_id' => (string) $user->id,
                'type' => 'student_intake',
            ],
            // Optional: automatic_payment_methods for flexibility
            'automatic_payment_methods' => ['enabled' => true],
        ]);

        $submission->update([
            'stripe_payment_intent_id' => $pi->id,
        ]);

        return response()->json([
            'submission_id' => $submission->id,
            'clientSecret' => $pi->client_secret,
            'amountCents' => $submission->amount_cents,
            'currency' => $submission->currency,
            'status' => $submission->status, // pending_payment
        ], 201);
    }

    // Optional: front-end can poll this after confirmation() or after webhook
    public function status(StudentIntakeSubmission $submission)
    {
        $this->authorize('view', $submission); // optional, or ensure owner
        return response()->json([
            'id' => $submission->id,
            'status' => $submission->status,
            'submitted' => !is_null($submission->submitted_at),
        ]);
    }
}