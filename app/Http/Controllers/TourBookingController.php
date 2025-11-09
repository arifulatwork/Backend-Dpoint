<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use App\Models\TourBooking;
use App\Models\TourPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class TourBookingController extends Controller
{
    // POST /api/auth/tour/book
    public function createPaymentIntent(Request $request)
    {
        try {
            $validated = $request->validate([
                'tour_id'     => 'required|exists:tours,id',
                'travelers'   => 'nullable|integer|min:1|max:50',
                'start_date'  => 'nullable|date',
                'notes'       => 'nullable|array',
            ]);

            $user = $request->user();
            if (!$user) return response()->json(['error' => 'Unauthorized'], 401);

            $tour = Tour::findOrFail($validated['tour_id']);
            $travelers = $validated['travelers'] ?? 1;

            // if already booked & paid, short-circuit
            $existing = TourBooking::where('user_id', $user->id)
                ->where('tour_id', $tour->id)
                ->where('paid', true)
                ->first();

            if ($existing) {
                return response()->json([
                    'message' => 'You have already booked this tour.',
                    'already_booked' => true
                ], 200);
            }

            // snapshot prices at booking time
            $unitPrice = (float) $tour->base_price;
            $subtotal  = $unitPrice * $travelers;
            $discount  = 0.0;
            $tax       = 0.0;
            $total     = $subtotal - $discount + $tax;

            // Create pending booking
            $booking = TourBooking::create([
                'user_id'        => $user->id,
                'tour_id'        => $tour->id,
                'start_date'     => $validated['start_date'] ?? null,
                'travelers'      => $travelers,
                'customer_notes' => $validated['notes'] ?? null,
                'unit_price'     => $unitPrice,
                'subtotal'       => $subtotal,
                'discount'       => $discount,
                'tax'            => $tax,
                'total_amount'   => $total,
                'currency'       => $tour->currency ?? 'EUR',
                'status'         => 'pending',
                'paid'           => false,
            ]);

            // Stripe payment intent
            Stripe::setApiKey(config('services.stripe.secret'));
            $amountCents = (int) round($total * 100);

            $paymentIntent = PaymentIntent::create([
                'amount'   => $amountCents,
                'currency' => strtolower($booking->currency),
                'metadata' => [
                    'tour_id'     => $tour->id,
                    'booking_id'  => $booking->id,
                    'user_id'     => $user->id,
                    'category'    => $tour->category,
                    'slug'        => $tour->slug,
                ],
            ]);

            // persist tour_payments row
            $payment = TourPayment::create([
                'tour_booking_id'   => $booking->id,
                'provider'          => 'stripe',
                'amount'            => $booking->total_amount,
                'currency'          => $booking->currency,
                'provider_intent_id'=> $paymentIntent->id,
                'status'            => 'processing',
                'provider_payload'  => null,
            ]);

            return response()->json([
                'client_secret' => $paymentIntent->client_secret,
                'booking_id'    => $booking->id,
                'payment_id'    => $payment->id,
            ]);
        } catch (\Throwable $e) {
            Log::error('Tour booking error', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Booking failed. Try again later.'], 500);
        }
    }

    // POST /api/auth/tour/confirm
    // (Optional if you rely on webhooks; useful for client-side confirmation)
    public function confirmPayment(Request $request)
    {
        try {
            $data = $request->validate([
                'payment_intent_id' => 'required|string',
                'booking_id'        => 'required|integer',
            ]);

            $booking = TourBooking::where('id', $data['booking_id'])
                ->where('user_id', Auth::id())
                ->first();

            if (!$booking) {
                return response()->json(['message' => 'Booking not found.'], 404);
            }

            $payment = TourPayment::where('tour_booking_id', $booking->id)
                ->where('provider', 'stripe')
                ->where('provider_intent_id', $data['payment_intent_id'])
                ->first();

            if (!$payment) {
                return response()->json(['message' => 'Payment not found.'], 404);
            }

            // In a real flow, you’d check PI status with Stripe or wait for webhook.
            $payment->update(['status' => 'succeeded']);
            $booking->markPaid();

            return response()->json(['message' => 'Payment confirmed.']);
        } catch (\Throwable $e) {
            \Log::error('Tour payment confirm error', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Payment confirmation failed.'], 500);
        }
    }

    // GET /api/auth/tour/my-bookings
    public function myBookings()
    {
        try {
            $user = Auth::user();
            if (!$user) return response()->json(['error' => 'Unauthorized'], 401);

            // Return tour_ids as your React currently expects
            $tourIds = TourBooking::where('user_id', $user->id)
                ->where('paid', true)
                ->pluck('tour_id');

            return response()->json($tourIds);
        } catch (\Throwable $e) {
            \Log::error('Fetching bookings failed', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Could not fetch bookings.'], 500);
        }
    }

    // GET /api/auth/tour/bookings/{id}
    public function show(int $id)
    {
        try {
            $booking = TourBooking::with(['user', 'tour'])
                ->where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            // mirror your previous response shape
            return response()->json([
                'id'                       => $booking->id,
                'trip_title'               => $booking->tour->title,
                'user_name'                => trim(($booking->user->first_name ?? '') . ' ' . ($booking->user->last_name ?? '')) ?: $booking->user->name,
                'stripe_payment_intent_id' => optional(
                    $booking->payments()->where('provider','stripe')->latest()->first()
                )->provider_intent_id,
                'paid'        => $booking->paid,
                'created_at'  => $booking->created_at,
                'meeting_point'=> $booking->tour->meeting_point ?? 'TBA',
                'note'        => 'Call Marta',
            ]);
        } catch (\Throwable $e) {
            \Log::error('Booking show error', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Could not retrieve booking details.'], 500);
        }
    }

    // --- Backward-compatible wrappers for your existing frontend ---

    // POST /api/auth/montenegro-trip/book
    public function createPaymentIntentMontenegro(Request $request)
    {
        // accept legacy payload: montenegro_tour_id → tour_id
        $request->merge(['tour_id' => $request->input('montenegro_tour_id')]);
        return $this->createPaymentIntent($request);
    }

    // GET /api/auth/montenegro-trip/my-bookings
    public function myBookingsMontenegro()
    {
        return $this->myBookings();
    }

    // GET /api/auth/montenegro-trip/booking/{id}
    public function showMontenegro(int $id)
    {
        return $this->show($id);
    }
}
