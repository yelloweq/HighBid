<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use Brick\Money\Money;
use Illuminate\Http\Request;
use Stripe\StripeClient;

class CustomerController extends Controller
{
    //create checkout for customer to pay for a won auction
    public function checkout(Request $request, Auction $auction): void
    {
        $stripe = new StripeClient(env('STRIPE_SECRET'));


        $session = $stripe->checkout->sessions->create([
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
}
