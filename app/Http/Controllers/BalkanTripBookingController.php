<?php

namespace App\Http\Controllers;

use App\Models\BalkanTripBooking;
use App\Models\BalkanTrip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class BalkanTripBookingController extends Controller
{
    /**
     * Create Stripe payment intent and booking entry
     */
    public function createPaymentIntent(Request $request)
    {
        try {
            $request->validate([
                'balkan_trip_id' => 'required|exists:balkan_trips,id',
            ]);

            $user = $request->user();
            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $tripId = $request->balkan_trip_id;

            // ✅ Prevent duplicate booking
            $existing = BalkanTripBooking::where('user_id', $user->id)
                ->where('balkan_trip_id', $tripId)
                ->where('paid', true)
                ->first();

            if ($existing) {
                return response()->json([
                    'message' => 'You have already booked this trip.',
                    'already_booked' => true
                ], 200);
            }

            $trip = BalkanTrip::findOrFail($tripId);

            Stripe::setApiKey(env('STRIPE_SECRET'));

            $paymentIntent = PaymentIntent::create([
                'amount' => intval($trip->price * 100),
                'currency' => 'eur',
                'metadata' => [
                    'trip_id' => $trip->id,
                    'user_id' => $user->id,
                ],
            ]);

            $booking = BalkanTripBooking::create([
                'user_id' => $user->id,
                'balkan_trip_id' => $trip->id,
                'stripe_payment_intent_id' => $paymentIntent->id,
                'paid' => false,
            ]);

            return response()->json([
                'client_secret' => $paymentIntent->client_secret,
                'booking_id' => $booking->id,
            ]);
        } catch (\Exception $e) {
            \Log::error('Balkan trip booking error:', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Booking failed. Please try again later.'], 500);
        }
    }

    /**
     * Confirm the payment after Stripe succeeds
     */
    public function confirmPayment(Request $request)
    {
        try {
            $request->validate([
                'payment_intent_id' => 'required|string',
            ]);

            $booking = BalkanTripBooking::where('stripe_payment_intent_id', $request->payment_intent_id)->first();

            if (!$booking) {
                return response()->json(['message' => 'Booking not found.'], 404);
            }

            $booking->update(['paid' => true]);

            return response()->json(['message' => 'Payment confirmed.']);
        } catch (\Exception $e) {
            \Log::error('Payment confirmation error:', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Payment confirmation failed.'], 500);
        }
    }

    /**
     * Get all PAID trip IDs of the authenticated user
     */
    public function myBookings()
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            // Return only IDs of trips already booked and paid
            $tripIds = BalkanTripBooking::where('user_id', $user->id)
                ->where('paid', true)
                ->pluck('balkan_trip_id');

            return response()->json($tripIds);
        } catch (\Exception $e) {
            \Log::error('Fetching bookings failed:', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Could not fetch bookings.'], 500);
        }
    }
}
