<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Internship;
use App\Models\InternshipEnrollment;
use App\Models\InternshipPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Checkout\Session as CheckoutSession;

class InternshipEnrollmentController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * POST /api/auth/internships/enroll/create-payment-intent
     * body: { internship_id: number }
     *
     * Returns either:
     *  - { checkout_url }  -> Stripe Checkout redirect
     *  - { client_secret, payment_intent_id } -> PaymentIntent (use Stripe.js to confirm)
     */
    public function createPaymentIntent(Request $request)
    {
        try {
            $user = $request->user();

            $data = $request->validate([
                'internship_id' => ['required','integer','exists:internships,id'],
            ]);

            $internship = Internship::findOrFail($data['internship_id']);

            // ----- Pricing (EUR) -----
            $amountEur   = (float) $internship->price;
            $amountCents = (int) round($amountEur * 100);
            $currency    = config('services.stripe.currency', 'eur');

            // Prevent duplicate enrollment (pending/processing/succeeded)
            $existing = InternshipEnrollment::where('user_id', $user->id)
                ->where('internship_id', $internship->id)
                ->whereIn('status', ['pending','processing','succeeded'])
                ->first();

            if ($existing && $existing->status === 'succeeded') {
                return response()->json([
                    'message' => 'You are already enrolled in this internship.'
                ], 409);
            }

            // Create or reuse enrollment row
            $enrollment = $existing ?? InternshipEnrollment::create([
                'user_id'       => $user->id,
                'internship_id' => $internship->id,
                'amount'        => $amountEur,
                'currency'      => $currency,
                'status'        => 'pending',
            ]);

            // ====== Choose your flow ======
            $useCheckout = true; // set to false to use the PaymentIntent flow

            if ($useCheckout) {
                // -------- Build & validate redirect URLs (required by Checkout) --------
                $success = config('services.stripe.success_url');
                $cancel  = config('services.stripe.cancel_url');

                // Fallback to APP_URL if envs are missing
                $appUrl = rtrim(config('app.url', 'http://127.0.0.1:8000'), '/');
                if (!$success) $success = $appUrl.'/payment/success';
                if (!$cancel)  $cancel  = $appUrl.'/payment/cancel';

                foreach (['success' => $success, 'cancel' => $cancel] as $k => $u) {
                    if (!filter_var($u, FILTER_VALIDATE_URL)) {
                        return response()->json([
                            'message' => "Invalid {$k}_url configured for Stripe Checkout.",
                            'url'     => $u,
                        ], 422);
                    }
                }

                // -------------------- Stripe Checkout --------------------
                $session = CheckoutSession::create([
                    'mode' => 'payment',
                    'payment_method_types' => ['card'],
                    'customer_email' => $user->email ?? null,
                    'line_items' => [[
                        'quantity' => 1,
                        'price_data' => [
                            'currency' => $currency,
                            'unit_amount' => $amountCents,
                            'product_data' => [
                                'name' => $internship->title,
                                'description' => 'Internship enrollment — '.$internship->company,
                            ],
                        ],
                    ]],
                    'metadata' => [
                        'app'            => 'internship_enrollment',
                        'enrollment_id'  => (string)$enrollment->id,
                        'user_id'        => (string)$user->id,
                        'internship_id'  => (string)$internship->id,
                    ],
                    'success_url' => $success.'?session_id={CHECKOUT_SESSION_ID}',
                    'cancel_url'  => $cancel,
                ]);

                // ✅ Placeholder payment log keyed by Checkout Session (PI can be null now)
                InternshipPayment::updateOrCreate(
                    ['stripe_checkout_session_id' => $session->id],
                    [
                        'enrollment_id' => $enrollment->id,
                        'stripe_payment_intent_id' => $session->payment_intent ?? null, // keep null if absent
                        'amount' => $amountEur,
                        'currency' => $currency,
                        'status' => 'pending',
                        'stripe_response' => $session->toArray(),
                    ]
                );

                return response()->json(['checkout_url' => $session->url]);
            }

            // -------------------- PaymentIntent (manual confirm via Stripe.js) --------------------
            $intent = PaymentIntent::create([
                'amount' => $amountCents,
                'currency' => $currency,
                'metadata' => [
                    'app'            => 'internship_enrollment',
                    'enrollment_id'  => (string)$enrollment->id,
                    'user_id'        => (string)$user->id,
                    'internship_id'  => (string)$internship->id,
                ],
                'automatic_payment_methods' => ['enabled' => true],
            ]);

            $enrollment->update([
                'stripe_payment_intent_id' => $intent->id,
                'status' => 'processing',
            ]);

            InternshipPayment::updateOrCreate(
                ['stripe_payment_intent_id' => $intent->id],
                [
                    'enrollment_id' => $enrollment->id,
                    'stripe_checkout_session_id' => null,
                    'amount' => $amountEur,
                    'currency' => $currency,
                    'status' => 'pending',
                    'stripe_response' => $intent->toArray(),
                ]
            );

            return response()->json([
                'client_secret' => $intent->client_secret,
                'payment_intent_id' => $intent->id,
            ]);

        } catch (\Stripe\Exception\ApiErrorException $e) {
            Log::error('Stripe error: '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['message' => 'Stripe error: '.$e->getMessage()], 500);
        } catch (\Throwable $e) {
            Log::error('Enroll error: '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * POST /api/auth/internships/enroll/confirm
     * body: { payment_intent_id: string }
     * Use only for the PaymentIntent (no Checkout) flow.
     */
    public function confirm(Request $request)
    {
        $data = $request->validate([
            'payment_intent_id' => ['required','string'],
        ]);

        try {
            $intent = PaymentIntent::retrieve($data['payment_intent_id']);
            $enrollment = InternshipEnrollment::where('stripe_payment_intent_id', $intent->id)->firstOrFail();

            if ($intent->status === 'succeeded') {
                DB::transaction(function () use ($enrollment, $intent) {
                    $enrollment->update([
                        'status' => 'succeeded',
                        'payment_completed_at' => now(),
                        'enrolled_at' => now(),
                        'payment_details' => [
                            'payment_method' => $intent->payment_method,
                            'charges' => $intent->charges?->data ?? [],
                        ],
                    ]);

                    InternshipPayment::updateOrCreate(
                        ['stripe_payment_intent_id' => $intent->id],
                        [
                            'enrollment_id' => $enrollment->id,
                            'stripe_checkout_session_id' => null,
                            'amount' => $enrollment->amount,
                            'currency' => $enrollment->currency,
                            'status' => 'succeeded',
                            'stripe_response' => $intent->toArray(),
                        ]
                    );
                });

                return response()->json(['status' => 'ok']);
            }

            if ($intent->status === 'processing') {
                $enrollment->update(['status' => 'processing']);
                return response()->json(['status' => 'processing']);
            }

            $enrollment->update([
                'status' => 'failed',
                'failure_message' => $intent->last_payment_error?->message,
            ]);

            InternshipPayment::updateOrCreate(
                ['stripe_payment_intent_id' => $intent->id],
                [
                    'enrollment_id' => $enrollment->id,
                    'stripe_checkout_session_id' => null,
                    'amount' => $enrollment->amount,
                    'currency' => $enrollment->currency,
                    'status' => 'failed',
                    'stripe_response' => $intent->toArray(),
                ]
            );

            return response()->json([
                'status' => 'failed',
                'message' => $intent->last_payment_error?->message,
            ], 422);

        } catch (\Throwable $e) {
            Log::error('Confirm error: '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Stripe webhook for both Checkout and PaymentIntent flows.
     * POST /api/stripe/webhook
     */
    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sig     = $request->header('Stripe-Signature');
        $secret  = config('services.stripe.webhook_secret');

        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sig, $secret);
        } catch (\Throwable $e) {
            Log::warning('Stripe webhook signature invalid: '.$e->getMessage());
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        switch ($event->type) {
            case 'checkout.session.completed': {
                /** @var \Stripe\Checkout\Session $session */
                $session = $event->data->object;

                $enrollmentId = (int) ($session->metadata->enrollment_id ?? 0);
                if ($enrollmentId) {
                    $enrollment = InternshipEnrollment::find($enrollmentId);
                    if ($enrollment) {
                        $enrollment->update([
                            'status' => 'succeeded',
                            'payment_completed_at' => now(),
                            'enrolled_at' => now(),
                            'stripe_payment_intent_id' => $session->payment_intent ?? $enrollment->stripe_payment_intent_id,
                            'payment_details' => [
                                'checkout_session_id' => $session->id,
                            ],
                        ]);

                        // ✅ upsert payment row by checkout_session_id
                        InternshipPayment::updateOrCreate(
                            ['stripe_checkout_session_id' => $session->id],
                            [
                                'enrollment_id' => $enrollment->id,
                                'stripe_payment_intent_id' => $session->payment_intent ?? null,
                                'amount' => $enrollment->amount,
                                'currency' => $enrollment->currency,
                                'status' => 'succeeded',
                                'stripe_response' => $session->toArray(),
                            ]
                        );
                    }
                }
                break;
            }

            case 'payment_intent.succeeded': {
                /** @var \Stripe\PaymentIntent $intent */
                $intent = $event->data->object;
                $enrollment = InternshipEnrollment::where('stripe_payment_intent_id', $intent->id)->first();

                if ($enrollment) {
                    $enrollment->update([
                        'status' => 'succeeded',
                        'payment_completed_at' => now(),
                        'enrolled_at' => now(),
                        'payment_details' => [
                            'payment_method' => $intent->payment_method,
                            'charges' => $intent->charges?->data ?? [],
                        ],
                    ]);

                    // ✅ upsert by PI id
                    InternshipPayment::updateOrCreate(
                        ['stripe_payment_intent_id' => $intent->id],
                        [
                            'enrollment_id' => $enrollment->id,
                            'stripe_checkout_session_id' => null,
                            'amount' => $enrollment->amount,
                            'currency' => $enrollment->currency,
                            'status' => 'succeeded',
                            'stripe_response' => $intent->toArray(),
                        ]
                    );
                }
                break;
            }

            case 'payment_intent.payment_failed': {
                /** @var \Stripe\PaymentIntent $intent */
                $intent = $event->data->object;
                $enrollment = InternshipEnrollment::where('stripe_payment_intent_id', $intent->id)->first();

                if ($enrollment) {
                    $enrollment->update([
                        'status' => 'failed',
                        'failure_message' => $intent->last_payment_error?->message,
                    ]);

                    InternshipPayment::updateOrCreate(
                        ['stripe_payment_intent_id' => $intent->id],
                        [
                            'enrollment_id' => $enrollment->id,
                            'stripe_checkout_session_id' => null,
                            'amount' => $enrollment->amount,
                            'currency' => $enrollment->currency,
                            'status' => 'failed',
                            'stripe_response' => $intent->toArray(),
                        ]
                    );
                }
                break;
            }
        }

        return response()->json(['received' => true]);
    }

    public function enrolledIds(Request $request)
    {
        $user = $request->user();

        $ids = InternshipEnrollment::where('user_id', $user->id)
            ->where('status', 'succeeded')
            ->pluck('internship_id')
            ->values();

        return response()->json(['enrolled_ids' => $ids]);
    }

    public function enrollmentDetails(Request $request, int $id)
    {
        $user = $request->user();

        $enrollment = InternshipEnrollment::with('internship')
            ->where('user_id', $user->id)
            ->where('internship_id', $id)
            ->first();

        if (!$enrollment) {
            return response()->json(['message' => 'Not enrolled'], 404);
        }

        return response()->json([
            'status' => $enrollment->status,
            'enrollment' => $enrollment,
        ]);
    }
}
