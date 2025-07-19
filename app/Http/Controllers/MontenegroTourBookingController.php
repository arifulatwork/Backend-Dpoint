<?php

namespace App\Http\Controllers;

use App\Models\MontenegroTourBooking;
use App\Models\MontenegroTour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class MontenegroTourBookingController extends Controller
{
    public function createPaymentIntent(Request $request)
    {
        try {
            $request->validate([
                'montenegro_tour_id' => 'required|exists:montenegro_tours,id',
            ]);

            $user = $request->user();
            if (!$user) return response()->json(['error' => 'Unauthorized'], 401);

            $tourId = $request->montenegro_tour_id;

            $existing = MontenegroTourBooking::where('user_id', $user->id)
                ->where('montenegro_tour_id', $tourId)
                ->where('paid', true)
                ->first();

            if ($existing) {
                return response()->json([
                    'message' => 'You have already booked this tour.',
                    'already_booked' => true
                ], 200);
            }

            $tour = MontenegroTour::findOrFail($tourId);
            Stripe::setApiKey(env('STRIPE_SECRET'));

            $paymentIntent = PaymentIntent::create([
                'amount' => intval($tour->price * 100),
                'currency' => 'eur',
                'metadata' => [
                    'tour_id' => $tour->id,
                    'user_id' => $user->id,
                ],
            ]);

            $booking = MontenegroTourBooking::create([
                'user_id' => $user->id,
                'montenegro_tour_id' => $tour->id,
                'stripe_payment_intent_id' => $paymentIntent->id,
                'paid' => false,
            ]);

            return response()->json([
                'client_secret' => $paymentIntent->client_secret,
                'booking_id' => $booking->id,
            ]);
        } catch (\Exception $e) {
            \Log::error('Montenegro tour booking error:', ['message' => $e->getMessage()]);
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

            $booking = MontenegroTourBooking::where('stripe_payment_intent_id', $request->payment_intent_id)
                ->where('id', $request->booking_id)
                ->first();

            if (!$booking) return response()->json(['message' => 'Booking not found.'], 404);

            $booking->update(['paid' => true]);

            return response()->json(['message' => 'Payment confirmed.']);
        } catch (\Exception $e) {
            \Log::error('Montenegro payment confirm error:', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Payment confirmation failed.'], 500);
        }
    }

    public function myBookings()
    {
        try {
            $user = Auth::user();
            if (!$user) return response()->json(['error' => 'Unauthorized'], 401);

            $tourIds = MontenegroTourBooking::where('user_id', $user->id)
                ->where('paid', true)
                ->pluck('montenegro_tour_id');

            return response()->json($tourIds);
        } catch (\Exception $e) {
            \Log::error('Fetching Montenegro bookings failed:', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Could not fetch bookings.'], 500);
        }
    }

    public function show($id)
    {
        try {
            $booking = MontenegroTourBooking::with(['user', 'montenegroTour'])
                ->where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            return response()->json([
                'id' => $booking->id,
                'trip_title' => $booking->montenegroTour->title,
                'user_name' => $booking->user->first_name . ' ' . $booking->user->last_name,
                'stripe_payment_intent_id' => $booking->stripe_payment_intent_id,
                'paid' => $booking->paid,
                'created_at' => $booking->created_at,
                'meeting_point' => $booking->montenegroTour->meeting_point ?? 'TBA',
                'note' => 'Call Marta',
            ]);
        } catch (\Exception $e) {
            \Log::error('Montenegro booking show error:', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Could not retrieve booking details.'], 500);
        }
    }
}
