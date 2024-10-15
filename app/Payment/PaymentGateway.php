<?php

namespace App\Payment;

use App\Models\Auction;
use App\Models\User;
use Brick\Money\Money;
use Exception;
use Illuminate\Support\Facades\Log;
use Mauricius\LaravelHtmx\Http\HtmxResponse;
use Stripe\BaseStripeClient;
use Stripe\StripeClient;

class PaymentGateway implements PaymentGatewayInterface
{
    protected BaseStripeClient $stripe;
    public function __construct()
    {
        $this->stripe = new StripeClient(env('STRIPE_SECRET'));
    }

    public function checkout(Auction $auction): void
    {
        try {
            $this->stripe->checkout->sessions->create([
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'gbp',
                        'product_data' => ['name' => $auction->title],
                        'unit_amount' => $auction->price,
                    ],
                    'quantity' => 1,
                ],
                ],
                'payment_intent_data' => [
                    'application_fee_amount' => Money::of(config('app.fee'), 'gbp'),
                    'transfer_data' => [ 'destination' => $auction->seller()->stripe_connect_id ],
                ],
                'mode' => 'payment',
                'success_url' => route('payment.checkout.success'),
            ]);
        }
        catch (\Exception $e) {
            Log::error("An error occurred for Auction with ID: {{ $auction->id }} calling the Stripe API checkout: {{ $e->getMessage() }} ");
        }
    }

    public function createSellerAccount(User $seller): string
    {
        try {
            $account = $this->stripe->accounts->create([
                'type' => 'express',
            ]);

            $connectId = $account->id;

            $seller->stripe_connect_id = $connectId;
            $seller->save();

            $account_link = $this->stripe->accountLinks->create([
                'account' => $connectId,
                'return_url' => route('stripe.return_url'),
                'refresh_url' => route('stripe.refresh_url'),
                'type' => 'account_onboarding',
            ]);

            return $account_link->url;
        }
        catch (Exception $e)
        {
            Log::error("An error occurred when calling the Stripe API to create an account: {{ $e->getMessage() }}");
        }
        return '';
    }
}
