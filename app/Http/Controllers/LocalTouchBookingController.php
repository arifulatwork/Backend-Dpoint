<?php

namespace App\Http\Controllers;

use App\Models\LocalTouchBooking;
use App\Models\LocalTouchPayment;
use App\Models\Experience;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Carbon\Carbon;
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

    // 🛑 Check if the user has already booked this experience for the same date
    $existingBooking = LocalTouchBooking::where('user_id', $user->id)
        ->where('experience_id', $experience->id)
        ->where('date', $request->date)
        ->first();

    if ($existingBooking) {
        return response()->json([
            'message' => 'You have already booked this experience for the selected date.'
        ], 422);
    }

    if ($request->participants > $experience->max_participants) {
        return response()->json(['message' => 'Too many participants'], 422);
    }

    // ✅ Create booking
    $booking = LocalTouchBooking::create([
        'user_id' => $user->id,
        'experience_id' => $experience->id,
        'date' => $request->date,
        'time' => $request->time,
        'participants' => $request->participants,
        'special_requests' => $request->special_requests,
        'status' => 'pending',
    ]);

    // 💳 Stripe setup
    Stripe::setApiKey(env('STRIPE_SECRET'));
    $totalAmount = $experience->price * $request->participants;

    $paymentIntent = PaymentIntent::create([
        'amount' => (int)($totalAmount * 100),
        'currency' => 'eur',
        'metadata' => [
            'booking_id' => $booking->id,
            'user_id' => $user->id,
            'experience_name' => $experience->name,
        ],
    ]);

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

public function experiencesWithBookingStatus()
{
    $user = Auth::user();
    $experiences = Experience::with(['bookings' => function ($query) use ($user) {
        $query->where('user_id', $user->id)->whereDate('date', '>=', Carbon::today());
    }])->get();

    $result = $experiences->map(function ($experience) use ($user) {
        $userBooking = $experience->bookings->first(); // may be null

        return array_merge($experience->toArray(), [
            'user_has_booking' => !!$userBooking,
            'user_booking_details' => $userBooking ? [
                'date' => $userBooking->date,
                'time' => $userBooking->time,
                'participants' => $userBooking->participants,
            ] : null,
        ]);
    });

    return response()->json($result);
}

}
