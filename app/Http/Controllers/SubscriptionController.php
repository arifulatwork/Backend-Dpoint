<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SubscriptionController extends Controller
{
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
            $startedAt = Carbon::now();
            $expiresAt = Carbon::now()->addMonth(); // Ensures not null

            Log::info('⏳ Creating subscription...', [
                'user_id' => $userId,
                'payment_id' => $request->payment_id,
                'tier_id' => $request->premium_tier_id,
                'expires_at' => $expiresAt->toDateTimeString()
            ]);

            // ✅ Create subscription
            $subscription = Subscription::create([
                'user_id' => $userId,
                'payment_id' => $request->payment_id,
                'premium_tier_id' => $request->premium_tier_id,
                'gateway_subscription_id' => $request->gateway_subscription_id,
                'status' => 'active',
                'started_at' => $startedAt,
                'expires_at' => $expiresAt,
            ]);

            Log::info('✅ Subscription created successfully.', [
                'subscription_id' => $subscription->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Subscription created successfully.',
                'subscription_id' => $subscription->id
            ]);
        } catch (\Exception $e) {
            Log::error('❌ Subscription creation failed.', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Subscription creation failed.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
