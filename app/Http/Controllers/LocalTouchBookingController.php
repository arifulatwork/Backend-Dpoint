<?php

namespace App\Http\Controllers;

use App\Models\LocalTouchBooking;
use App\Models\LocalTouchPayment;
use App\Models\Experience;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class LocalTouchBookingController extends Controller
{
    public function bookAndPay(Request $request)
    {
        $request->validate([
            'experience_id' => 'required|exists:experiences,id',
            'date' => 'required|date',
            'time' => 'required',
            'participants' => 'required|integer|min:1',
            'special_requests' => 'nullable|string',
        ]);

        $user = Auth::user();
        $experience = Experience::findOrFail($request->experience_id);

        if ($request->participants > $experience->max_participants) {
            return response()->json(['message' => 'Too many participants'], 422);
        }

        // Create booking
        $booking = LocalTouchBooking::create([
            'user_id' => $user->id,
            'experience_id' => $experience->id,
            'date' => $request->date,
            'time' => $request->time,
            'participants' => $request->participants,
            'special_requests' => $request->special_requests,
            'status' => 'pending',
        ]);

        // Stripe setup
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $totalAmount = $experience->price * $request->participants;

        // Create PaymentIntent
        $paymentIntent = PaymentIntent::create([
            'amount' => (int)($totalAmount * 100), // in cents
            'currency' => 'eur',
            'metadata' => [
                'booking_id' => $booking->id,
                'user_id' => $user->id,
                'experience_name' => $experience->name,
            ],
        ]);

        // Store payment record
        LocalTouchPayment::create([
            'booking_id' => $booking->id,
            'stripe_payment_intent_id' => $paymentIntent->id,
            'amount' => $totalAmount,
            'status' => 'pending',
        ]);

        return response()->json([
            'client_secret' => $paymentIntent->client_secret,
            'booking_id' => $booking->id,
        ]);
    }
}
