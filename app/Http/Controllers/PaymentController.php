<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Charge;
use Stripe\Exception\CardException;
use Stripe\Exception\RateLimitException;
use Stripe\Exception\InvalidRequestException;
use Stripe\Exception\AuthenticationException;
use Stripe\Exception\ApiConnectionException;
use Stripe\Exception\ApiErrorException;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function charge(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'amount' => 'required|numeric|min:1', // amount in EUR (e.g. 10.00)
            'purpose' => 'required|string|max:255',
            'tier_id' => 'nullable|exists:premium_tiers,id'
        ]);

        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            // Convert amount to cents for Stripe
            $amountInCents = (int)($request->amount * 100);

            $charge = Charge::create([
                'amount' => $amountInCents,
                'currency' => 'eur',
                'source' => $request->token,
                'description' => 'Premium Plan: ' . $request->purpose,
                'metadata' => [
                    'user_id' => auth()->id(),
                    'purpose' => $request->purpose,
                    'tier_id' => $request->tier_id,
                    'amount_eur' => $request->amount, // Store original amount in EUR
                ],
                'receipt_email' => auth()->user()->email,
            ]);

            // Record successful payment
            $payment = Payment::create([
                'user_id' => auth()->id(),
                'status' => $charge->paid ? 'succeeded' : 'failed',
                'payment_gateway' => 'stripe',
                'transaction_id' => $charge->id,
                'amount' => $request->amount, // Store in original currency units (EUR)
                'currency' => strtoupper($charge->currency),
                'purpose' => $request->purpose,
                'tier_id' => $request->tier_id,
                'metadata' => json_encode($charge->metadata),
                'payment_details' => json_encode([
                    'brand' => $charge->payment_method_details->card->brand ?? null,
                    'last4' => $charge->payment_method_details->card->last4 ?? null,
                    'country' => $charge->payment_method_details->card->country ?? null,
                ]),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment successful',
                'payment_id' => $payment->id,
                'charge_id' => $charge->id
            ]);

        } catch (CardException $e) {
            Log::error('Stripe Card Error: ' . $e->getMessage());
            return response()->json([
                'error' => 'card_error',
                'message' => $e->getError()->message ?? 'Your card was declined.'
            ], 400);
            
        } catch (RateLimitException $e) {
            Log::error('Stripe Rate Limit: ' . $e->getMessage());
            return response()->json([
                'error' => 'rate_limit',
                'message' => 'Too many requests. Please try again later.'
            ], 429);
            
        } catch (InvalidRequestException $e) {
            Log::error('Stripe Invalid Request: ' . $e->getMessage());
            return response()->json([
                'error' => 'invalid_request',
                'message' => 'Invalid payment parameters.'
            ], 400);
            
        } catch (AuthenticationException $e) {
            Log::error('Stripe Authentication Error: ' . $e->getMessage());
            return response()->json([
                'error' => 'authentication_error',
                'message' => 'Payment authentication failed.'
            ], 401);
            
        } catch (ApiConnectionException $e) {
            Log::error('Stripe API Connection Error: ' . $e->getMessage());
            return response()->json([
                'error' => 'api_error',
                'message' => 'Network problem with Stripe.'
            ], 500);
            
        } catch (ApiErrorException $e) {
            Log::error('Stripe API Error: ' . $e->getMessage());
            return response()->json([
                'error' => 'stripe_error',
                'message' => 'Something went wrong with Stripe.'
            ], 500);
            
        } catch (\Exception $e) {
            Log::error('Payment Processing Error: ' . $e->getMessage());
            return response()->json([
                'error' => 'processing_error',
                'message' => 'Something went wrong with payment processing.'
            ], 500);
        }
    }
}