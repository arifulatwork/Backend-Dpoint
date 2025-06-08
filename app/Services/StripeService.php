<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\Subscription;
use Stripe\PaymentIntent;

class StripeService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function createCustomer($user, $token)
    {
        return Customer::create([
            'email' => $user->email,
            'source' => $token,
        ]);
    }

    public function createOneTimePayment($amount, $currency, $customerId, $description = '')
    {
        return Charge::create([
            'amount' => $amount * 100, // Stripe uses cents
            'currency' => $currency,
            'customer' => $customerId,
            'description' => $description,
        ]);
    }

    public function createSubscription($customerId, $priceId)
    {
        return Subscription::create([
            'customer' => $customerId,
            'items' => [['price' => $priceId]],
        ]);
    }

    public function createPaymentIntent($amount, $currency = 'usd')
    {
        return PaymentIntent::create([
            'amount' => $amount * 100,
            'currency' => $currency,
        ]);
    }
}
