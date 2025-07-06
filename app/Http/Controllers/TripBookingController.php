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
    $userId = Auth::id();

    // ✅ Check if already booked and paid
    $alreadyBooked = TripBooking::where('user_id', $userId)
        ->where('trip_id', $trip->id)
        ->where('status', 'paid') // Optional: Only block if already paid
        ->exists();

    if ($alreadyBooked) {
        return response()->json([
            'message' => 'Trip already booked and paid'
        ], 409); // HTTP 409 Conflict
    }

    // 🆕 Optionally check if there's a pending booking already
    $existingPending = TripBooking::where('user_id', $userId)
        ->where('trip_id', $trip->id)
        ->where('status', 'pending')
        ->first();

    if ($existingPending) {
        return response()->json([
            'message' => 'You already have a pending booking. Complete payment.'
        ], 409);
    }

    // ✅ Create a new booking
    $booking = TripBooking::create([
        'trip_id' => $trip->id,
        'user_id' => $userId,
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


    // ✅ Return all slugs of trips that the user has already paid for
    public function bookedTrips()
    {
        $userId = Auth::id();

        $bookedTripSlugs = Trip::whereIn('id', function ($query) use ($userId) {
            $query->select('trip_id')
                ->from('trip_bookings')
                ->where('user_id', $userId)
                ->where('status', 'paid');
        })->pluck('slug');

        return response()->json($bookedTripSlugs);
    }

    // Add to TripBookingController
        public function bookingDetails($slug)
        {
            $userId = Auth::id();

            $trip = Trip::where('slug', $slug)->firstOrFail();

            $booking = TripBooking::where('user_id', $userId)
                ->where('trip_id', $trip->id)
                ->where('status', 'paid')
                ->with('trip') // eager load trip
                ->latest()
                ->first();

            if (!$booking) {
                return response()->json(['message' => 'No paid booking found'], 404);
            }

            return response()->json([
                'booking_id' => $booking->id,
                'trip_title' => $booking->trip->title,
                'participants' => $booking->participants,
                'booking_date' => $booking->booking_date,
                'meeting_point' => $booking->trip->meeting_point ?? '',
                'duration_days' => $booking->trip->duration_days,
                'price' => $booking->trip->price,
            ]);
        }


}
