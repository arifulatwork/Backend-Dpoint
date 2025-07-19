<?php

namespace App\Http\Controllers;

use App\Models\PetraTourBooking;
use App\Models\PetraTour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class PetraTourBookingController extends Controller
{
    public function createPaymentIntent(Request $request)
    {
        try {
            $request->validate([
                'petra_tour_id' => 'required|exists:petra_tours,id',
            ]);

            $user = $request->user();
            if (!$user) return response()->json(['error' => 'Unauthorized'], 401);

            $tourId = $request->petra_tour_id;

            $existing = PetraTourBooking::where('user_id', $user->id)
                ->where('petra_tour_id', $tourId)
                ->where('paid', true)
                ->first();

            if ($existing) {
                return response()->json([
                    'message' => 'You have already booked this tour.',
                    'already_booked' => true
                ], 200);
            }

            $tour = PetraTour::findOrFail($tourId);
            Stripe::setApiKey(env('STRIPE_SECRET'));

            $paymentIntent = PaymentIntent::create([
                'amount' => intval($tour->price * 100),
                'currency' => 'eur',
                'metadata' => [
                    'tour_id' => $tour->id,
                    'user_id' => $user->id,
                ],
            ]);

            $booking = PetraTourBooking::create([
                'user_id' => $user->id,
                'petra_tour_id' => $tour->id,
                'stripe_payment_intent_id' => $paymentIntent->id,
                'paid' => false,
            ]);

            return response()->json([
                'client_secret' => $paymentIntent->client_secret,
                'booking_id' => $booking->id,
            ]);
        } catch (\Exception $e) {
            \Log::error('Petra tour booking error:', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Booking failed. Try again later.'], 500);
        }
    }

    public function confirmPayment(Request $request)
    {
        try {
            $request->validate([
                'payment_intent_id' => 'required|string',
                'booking_id' => 'required|integer',
            ]);

            $booking = PetraTourBooking::where('stripe_payment_intent_id', $request->payment_intent_id)
                ->where('id', $request->booking_id)
                ->first();

            if (!$booking) return response()->json(['message' => 'Booking not found.'], 404);

            $booking->update(['paid' => true]);

            return response()->json(['message' => 'Payment confirmed.']);
        } catch (\Exception $e) {
            \Log::error('Petra payment confirm error:', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Payment confirmation failed.'], 500);
        }
    }

    public function myBookings()
    {
        try {
            $user = Auth::user();
            if (!$user) return response()->json(['error' => 'Unauthorized'], 401);

            $tourIds = PetraTourBooking::where('user_id', $user->id)
                ->where('paid', true)
                ->pluck('petra_tour_id');

            return response()->json($tourIds);
        } catch (\Exception $e) {
            \Log::error('Fetching Petra bookings failed:', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Could not fetch bookings.'], 500);
        }
    }

    public function show($id)
    {
        try {
            $booking = PetraTourBooking::with(['user', 'petraTour'])
                ->where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            return response()->json([
                'id' => $booking->id,
                'trip_title' => $booking->petraTour->title,
                'user_name' => $booking->user->first_name . ' ' . $booking->user->last_name,
                'stripe_payment_intent_id' => $booking->stripe_payment_intent_id,
                'paid' => $booking->paid,
                'created_at' => $booking->created_at,
                'meeting_point' => $booking->petraTour->meeting_point ?? 'TBA',
                'note' => 'Call Marta',
            ]);
        } catch (\Exception $e) {
            \Log::error('Petra booking show error:', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Could not retrieve booking details.'], 500);
        }
    }
}
