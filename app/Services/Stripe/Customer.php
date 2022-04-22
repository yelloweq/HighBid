<?php

namespace App\Services\Stripe;

use App\Models\User;
use Stripe\Stripe;
use Stripe\Token;

class Customer
{
    public static function save(User $user, $token)
    {
        Stripe::setApiKey(env('STRIPE_SK'));

        $customer = \Stripe\Customer::create([
            'source' => $token,
            'email' => $user->email,
        ]);

        $user->update([
            'stripe_customer_id' => $customer->id,
        ]);
    }
}
