<?php

namespace App\Http\Controllers;

use App\Models\TourBooking;
use App\Models\TourPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Webhook;

class TourStripeWebhookController extends Controller
{
    /**
     * Handle Stripe Webhook Events for Tour Payments
     *
     * Route: POST /api/stripe/tour/webhook
     */
    public function handle(Request $request)
    {
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent(
                $request->getContent(),
                $sigHeader,
                $endpointSecret
            );
        } catch (\Throwable $e) {
            Log::warning('Stripe webhook signature verification failed', [
                'error' => $e->getMessage(),
            ]);
            return response('Invalid signature', 400);
        }

        $type = $event->type;
        $object = $event->data->object;

        switch ($type) {
            case 'payment_intent.succeeded':
                $this->handlePaymentSucceeded($object, $event);
                break;

            case 'payment_intent.payment_failed':
                $this->handlePaymentFailed($object, $event);
                break;

            case 'charge.refunded':
                $this->handleRefund($object, $event);
                break;

            default:
                Log::info("Unhandled Stripe webhook event: {$type}");
        }

        return response()->json(['received' => true]);
    }

    /**
     * Handle successful Stripe PaymentIntent.
     */
    protected function handlePaymentSucceeded($pi, $event)
    {
        $bookingId = (int) ($pi->metadata->booking_id ?? 0);
        if (!$bookingId) {
            Log::warning('Stripe PI succeeded but no booking_id in metadata', [
                'payment_intent_id' => $pi->id,
            ]);
            return;
        }

        $booking = TourBooking::find($bookingId);
        if (!$booking) {
            Log::warning('Booking not found for succeeded payment', [
                'booking_id' => $bookingId,
            ]);
            return;
        }

        // Update or create payment record
        $payment = TourPayment::firstOrNew([
            'provider' => 'stripe',
            'provider_intent_id' => $pi->id,
        ]);

        $payment->fill([
            'tour_booking_id' => $booking->id,
            'amount'          => $booking->total_amount,
            'currency'        => $booking->currency,
            'status'          => 'succeeded',
            'provider_payload'=> $event->data->toArray(),
            'receipt_url'     => $pi->charges->data[0]->receipt_url ?? null,
        ])->save();

        // Mark booking as paid
        $booking->markPaid();

        Log::info('✅ Stripe payment succeeded', [
            'booking_id' => $bookingId,
            'payment_intent' => $pi->id,
        ]);
    }

    /**
     * Handle failed payments.
     */
    protected function handlePaymentFailed($pi, $event)
    {
        $bookingId = (int) ($pi->metadata->booking_id ?? 0);
        if (!$bookingId) return;

        $booking = TourBooking::find($bookingId);
        if (!$booking) return;

        $payment = TourPayment::where('provider_intent_id', $pi->id)->first();
        if ($payment) {
            $payment->update([
                'status' => 'failed',
                'provider_payload' => $event->data->toArray(),
            ]);
        }

        Log::warning('❌ Stripe payment failed', [
            'booking_id' => $bookingId,
            'intent_id' => $pi->id,
        ]);
    }

    /**
     * Handle refunds.
     */
    protected function handleRefund($charge, $event)
    {
        $intentId = $charge->payment_intent ?? null;
        if (!$intentId) return;

        $payment = TourPayment::where('provider_intent_id', $intentId)->first();
        if (!$payment) return;

        $refundAmount = $charge->amount_refunded / 100;
        $payment->update([
            'status' => $refundAmount >= $payment->amount ? 'refunded' : 'partially_refunded',
            'refunded_amount' => $refundAmount,
            'provider_payload' => $event->data->toArray(),
        ]);

        // Optionally mark booking refunded
        $payment->booking?->update(['status' => 'refunded']);

        Log::info('💸 Stripe refund processed', [
            'payment_id' => $payment->id,
            'refund_amount' => $refundAmount,
        ]);
    }
}
