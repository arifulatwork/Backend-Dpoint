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

            $user = $request->user(); // Authenticated user via Sanctum
            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $trip = BalkanTrip::findOrFail($request->balkan_trip_id);

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
     * Get all bookings of the authenticated user (optional helper route)
     */
    public function myBookings()
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $bookings = BalkanTripBooking::with('balkanTrip')->where('user_id', $user->id)->get();

            return response()->json($bookings);
        } catch (\Exception $e) {
            \Log::error('Fetching bookings failed:', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Could not fetch bookings.'], 500);
        }
    }
}
