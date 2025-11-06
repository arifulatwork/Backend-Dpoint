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

        // --- 0) Prevent multiple active submissions ---
        // Treat these statuses as "active" (i.e., block creating a new one)
        $activeStatuses = [
            'pending_payment',
            'payment_requires_action',
            'payment_failed',
            'paid',
            'under_review',
            'submitted',
        ];

        /** @var \App\Models\StudentIntakeSubmission|null $active */
        $active = StudentIntakeSubmission::where('user_id', $user->id)
            ->whereIn('status', $activeStatuses)
            ->latest('id')
            ->first();

        if ($active) {
            // If payment isn't completed, allow resuming the same PaymentIntent — but first check Stripe status
            if (in_array($active->status, ['pending_payment', 'payment_requires_action', 'payment_failed'])) {
                \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

                // Retrieve or create PI for the existing submission
                if ($active->stripe_payment_intent_id) {
                    $pi = \Stripe\PaymentIntent::retrieve($active->stripe_payment_intent_id);
                } else {
                    $pi = \Stripe\PaymentIntent::create([
                        'amount'   => $active->amount_cents,
                        'currency' => $active->currency,
                        'metadata' => [
                            'submission_id' => (string) $active->id,
                            'user_id'       => (string) $user->id,
                            'type'          => 'student_intake',
                        ],
                        'automatic_payment_methods' => ['enabled' => true],
                    ]);
                    $active->update(['stripe_payment_intent_id' => $pi->id]);
                }

                // If Stripe already shows paid (or equivalent), don't reopen payment
                if (in_array($pi->status, ['succeeded', 'processing', 'requires_capture'])) {
                    // Optionally sync local status if webhook hasn't yet
                    if ($pi->status === 'succeeded' && $active->status !== 'paid') {
                        $active->update(['status' => 'paid']);
                    }

                    return response()->json([
                        'message'       => 'Your payment is already completed. Please wait for our response.',
                        'submission_id' => $active->id,
                        'status'        => $active->status,
                        'stripe_status' => $pi->status,
                    ], 409);
                }

                // Otherwise still unpaid → return client secret to resume
                return response()->json([
                    'submission_id' => $active->id,
                    'clientSecret'  => $pi->client_secret,
                    'amountCents'   => $active->amount_cents,
                    'currency'      => $active->currency,
                    'status'        => $active->status,
                    'stripe_status' => $pi->status,
                    'message'       => 'You already started an application. Please complete the existing payment to proceed.',
                ], 200);
            }

            // Otherwise block (already paid/submitted/under review)
            return response()->json([
                'message'       => 'You already submitted. Please wait for our response before creating a new application.',
                'submission_id' => $active->id,
                'status'        => $active->status,
            ], 409);
        }

        // --- 1) Validate new submission payload ---
        $validated = $request->validate([
            'fullName'           => 'required|string|max:255',
            'email'              => 'required|email',
            'contactPhone'       => 'nullable|string|max:50',
            'nationality'        => 'required|string|max:100',
            'currentLocation'    => 'required|string|max:100',
            'visaStatus'         => 'required|string|max:50',
            'visaExpiryDate'     => 'nullable|date',
            'hasResidenceCard'   => 'required|string|max:50',
            'studentStatus'      => 'required|string|max:100',
            'hasAccommodation'   => 'nullable|string|max:20',
            'hasHealthInsurance' => 'nullable|string|max:20',
            'hasEmpadronamiento' => 'nullable|string|max:20',
            'services_needed'    => 'nullable',
            'additionalInfo'     => 'nullable|string',
            'documents.*'        => 'file|max:5120|mimes:pdf,jpg,jpeg,png',
        ]);

        // --- 2) Normalize + pricing ---
        $servicesNeeded = $request->has('services_needed')
            ? json_decode($request->input('services_needed', '[]'), true)
            : [];

        // NOTE: Default is €90 (9000 cents). If you want €99, set INTAKE_BASE_PRICE_CENTS=9900 in .env
        $base  = (int) env('INTAKE_BASE_PRICE_CENTS', 9000);
        $addon = (int) env('INTAKE_SERVICE_ADDON_CENTS', 0);
        $count = is_array($servicesNeeded) ? count($servicesNeeded) : 0;
        $amountCents = $base + max(0, $count - 1) * $addon;

        // --- 3) Store uploaded docs to a temp folder (pre-payment) ---
        $tmpFolder = 'student_intake/tmp/' . Str::uuid()->toString();
        $paths = [];
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                $paths[] = $file->store($tmpFolder, 'public');
            }
        }

        // --- 4) Create submission ---
        $submission = StudentIntakeSubmission::create([
            'user_id'              => $user->id,
            'full_name'            => $validated['fullName'],
            'email'                => $validated['email'],
            'contact_phone'        => $validated['contactPhone'] ?? null,
            'nationality'          => $validated['nationality'],
            'current_location'     => $validated['currentLocation'],
            'visa_status'          => $validated['visaStatus'],
            'visa_expiry_date'     => $validated['visaExpiryDate'] ?? null,
            'has_residence_card'   => $validated['hasResidenceCard'],
            'student_status'       => $validated['studentStatus'],
            'has_accommodation'    => $validated['hasAccommodation'] ?? null,
            'has_health_insurance' => $validated['hasHealthInsurance'] ?? null,
            'has_empadronamiento'  => $validated['hasEmpadronamiento'] ?? null,
            'services_needed'      => $servicesNeeded,
            'additional_info'      => $validated['additionalInfo'] ?? null,
            'document_paths'       => $paths,
            'amount_cents'         => $amountCents,
            'currency'             => config('services.stripe.currency', 'eur'),
            'status'               => 'pending_payment',
        ]);

        // --- 5) Create PaymentIntent ---
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
        $pi = \Stripe\PaymentIntent::create([
            'amount'   => $submission->amount_cents,
            'currency' => $submission->currency,
            'metadata' => [
                'submission_id' => (string) $submission->id,
                'user_id'       => (string) $user->id,
                'type'          => 'student_intake',
            ],
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
