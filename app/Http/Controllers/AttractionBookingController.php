<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attraction;
use App\Models\AttractionBooking;
use App\Models\AttractionPayment;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class AttractionBookingController extends Controller
{
    // 🟢 Step 1: Create Booking with duplicate check
    public function book(Request $request, $attractionId)
    {
        $userId = Auth::id();
        $attraction = Attraction::findOrFail($attractionId);

        // 🛑 Prevent duplicate paid booking
        $existingBooking = AttractionBooking::where('user_id', $userId)
            ->where('attraction_id', $attraction->id)
            ->where('status', 'paid')
            ->first();

        if ($existingBooking) {
            return response()->json([
                'message' => 'Attraction already booked and paid for.',
                'booking_id' => $existingBooking->id,
                'amount' => $attraction->price,
                'already_paid' => true,
            ], 200);
        }

        // ✅ Proceed to book
        $booking = AttractionBooking::create([
            'attraction_id' => $attraction->id,
            'user_id' => $userId,
            'participants' => $request->input('participants', 1),
            'booking_date' => now(),
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Booking created',
            'booking_id' => $booking->id,
            'amount' => $attraction->price,
            'already_paid' => false,
        ]);
    }

    // 🟢 Step 2: Create Stripe Payment Intent
    public function createPaymentIntent(Request $request)
    {
        $booking = AttractionBooking::findOrFail($request->booking_id);

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $intent = PaymentIntent::create([
            'amount' => intval($booking->attraction->price * 100), // in cents
            'currency' => 'eur',
            'metadata' => [
                'booking_id' => $booking->id,
                'user_id' => $booking->user_id,
            ],
        ]);

        return response()->json([
            'clientSecret' => $intent->client_secret,
        ]);
    }

    // 🟢 Step 3: Confirm and Save Payment
    public function confirmPayment(Request $request)
    {
        $booking = AttractionBooking::findOrFail($request->booking_id);

        AttractionPayment::create([
            'booking_id' => $booking->id,
            'payment_intent_id' => $request->payment_intent_id,
            'payment_method' => $request->payment_method,
            'amount' => $request->amount,
            'currency' => 'eur',
            'status' => 'succeeded',
        ]);

        $booking->update(['status' => 'paid']);

        return response()->json(['message' => 'Payment confirmed and saved']);
    }

    // 🔹 Get User's Booked Attractions
    public function myBookings()
    {
        $bookings = AttractionBooking::with('attraction')
            ->where('user_id', Auth::id())
            ->where('status', 'paid')
            ->get();

        return response()->json($bookings);
    }
}