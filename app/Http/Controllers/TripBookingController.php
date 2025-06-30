<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Trip;
use App\Models\TripBooking;
use App\Models\TripPayment;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class TripBookingController extends Controller
{
    // ✅ Book a trip (creates a booking record)
    public function book(Request $request, $slug)
    {
        $trip = Trip::where('slug', $slug)->firstOrFail();

        $booking = TripBooking::create([
            'trip_id' => $trip->id,
            'user_id' => Auth::id(),
            'participants' => $request->input('participants', 1),
            'booking_date' => now(),
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Booking created',
            'booking_id' => $booking->id,
            'amount' => $trip->price,
        ]);
    }

    // ✅ Create Stripe Payment Intent
    public function createPaymentIntent(Request $request)
    {
        $booking = TripBooking::where('id', $request->booking_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $trip = $booking->trip;

        $amount = $trip->price * 100; // Stripe accepts amount in cents

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $paymentIntent = PaymentIntent::create([
            'amount' => $amount,
            'currency' => 'usd',
            'metadata' => [
                'booking_id' => $booking->id
            ]
        ]);

        TripPayment::create([
            'booking_id' => $booking->id,
            'stripe_payment_intent_id' => $paymentIntent->id,
            'amount' => $amount / 100,
            'currency' => 'usd',
            'status' => 'pending',
        ]);

        return response()->json([
            'clientSecret' => $paymentIntent->client_secret
        ]);
    }

    // ✅ Confirm payment after Stripe success
    public function confirmPayment(Request $request)
    {
        $payment = TripPayment::where('stripe_payment_intent_id', $request->payment_intent_id)->firstOrFail();

        $payment->status = 'succeeded';
        $payment->save();

        $payment->booking->status = 'paid';
        $payment->booking->save();

        return response()->json(['message' => 'Payment confirmed']);
    }
}
