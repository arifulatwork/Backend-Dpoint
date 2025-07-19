<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SubscriptionController extends Controller
{
    /**
     * Store a new subscription or return existing active one.
     */
    public function store(Request $request)
    {
        // ✅ Validate required input
        $request->validate([
            'payment_id' => 'required|exists:payments,id',
            'gateway_subscription_id' => 'required|string',
            'premium_tier_id' => 'required|exists:premium_tiers,id',
        ]);

        try {
            $userId = Auth::id();
            $now = Carbon::now();

            // ✅ Check for existing active subscription
            $existingSubscription = Subscription::where('user_id', $userId)
                ->where('status', 'active')
                ->where('expires_at', '>', $now)
                ->latest('expires_at')
                ->first();

            if ($existingSubscription) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are already subscribed.',
                    'subscription_end_date' => Carbon::parse($existingSubscription->expires_at)->toDateString(),
                ], 200);
            }

            // ✅ Create new subscription
            $expiresAt = $now->addMonth(); // Can be dynamic based on tier.period later

            Log::info('⏳ Creating new subscription', [
                'user_id' => $userId,
                'payment_id' => $request->payment_id,
                'tier_id' => $request->premium_tier_id,
                'expires_at' => $expiresAt->toDateTimeString(),
            ]);

            $subscription = Subscription::create([
                'user_id' => $userId,
                'payment_id' => $request->payment_id,
                'premium_tier_id' => $request->premium_tier_id,
                'gateway_subscription_id' => $request->gateway_subscription_id,
                'status' => 'active',
                'started_at' => $now,
                'expires_at' => $expiresAt,
            ]);

            Log::info('✅ Subscription created', [
                'subscription_id' => $subscription->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Subscription created successfully.',
                'subscription_id' => $subscription->id,
                'expires_at' => Carbon::parse($subscription->expires_at)->toDateString(),
            ]);
        } catch (\Exception $e) {
            Log::error('❌ Subscription creation failed', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Subscription creation failed.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
