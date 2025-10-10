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
            'fullName'          => 'required|string|max:255',
            'email'             => 'required|email',
            'contactPhone'      => 'nullable|string|max:50',
            'nationality'       => 'required|string|max:100',
            'targetCountry'     => 'nullable|string|max:100',
            'currentSituation'  => 'nullable|string|max:100',
            'visaExpiryDate'    => 'nullable|date',
            'hasResidenceCard'  => 'nullable|string|max:50',
            'services_needed'   => 'nullable',
            'professionalInfo'  => 'nullable|string',
            'futurePlans'       => 'nullable|string|max:100',
            'documents.*'       => 'file|max:5120|mimes:pdf,jpg,jpeg,png',
        ]);

        // Normalize front-end naming (your form uses hasResidenceCard vs hasResidenceCard, servicesNeeded JSON)
        $servicesNeeded = $request->has('services_needed')
            ? json_decode($request->input('services_needed', '[]'), true)
            : [];

        // Price calculation (example): base + per service addon after the first
        $base  = (int) env('INTAKE_BASE_PRICE_CENTS', 1999);
        $addon = (int) env('INTAKE_SERVICE_ADDON_CENTS', 499);
        $count = is_array($servicesNeeded) ? count($servicesNeeded) : 0;
        $amountCents = $base + max(0, $count - 1) * $addon;

        // Save uploaded files to a temp folder tied to submission (pre-PAYMENT)
        $tmpFolder = 'student_intake/tmp/' . Str::uuid()->toString();
        $paths = [];
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                $paths[] = $file->store($tmpFolder, 'public'); // e.g., storage/app/public/...
            }
        }

        $submission = StudentIntakeSubmission::create([
            'user_id'            => $user->id,
            'full_name'          => $validated['fullName'],
            'email'              => $validated['email'],
            'contact_phone'      => $validated['contactPhone'] ?? null,
            'nationality'        => $validated['nationality'],
            'target_country'     => $validated['targetCountry'] ?? null,
            'current_situation'  => $validated['currentSituation'] ?? null,
            'visa_expiry_date'   => $validated['visaExpiryDate'] ?? null,
            'has_residence_card' => $validated['hasResidenceCard'] ?? null,
            'services_needed'    => $servicesNeeded,
            'professional_info'  => $validated['professionalInfo'] ?? null,
            'future_plans'       => $validated['futurePlans'] ?? null,
            'document_paths'     => $paths,
            'amount_cents'       => $amountCents,
            'currency'           => config('services.stripe.currency', 'eur'),
            'status'             => 'pending_payment',
        ]);

        // Create PaymentIntent
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        $pi = \Stripe\PaymentIntent::create([
            'amount'   => $submission->amount_cents,
            'currency' => $submission->currency,
            'metadata' => [
                'submission_id' => (string) $submission->id,
                'user_id'       => (string) $user->id,
                'type'          => 'student_intake',
            ],
            // Optional: automatic_payment_methods for flexibility
            'automatic_payment_methods' => ['enabled' => true],
        ]);

        $submission->update([
            'stripe_payment_intent_id' => $pi->id,
        ]);

        return response()->json([
            'submission_id' => $submission->id,
            'clientSecret'  => $pi->client_secret,
            'amountCents'   => $submission->amount_cents,
            'currency'      => $submission->currency,
            'status'        => $submission->status, // pending_payment
        ], 201);
    }

    // Optional: front-end can poll this after confirmation() or after webhook
    public function status(StudentIntakeSubmission $submission)
    {
        $this->authorize('view', $submission); // optional, or ensure owner
        return response()->json([
            'id'        => $submission->id,
            'status'    => $submission->status,
            'submitted' => !is_null($submission->submitted_at),
        ]);
    }
}
