<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class StripeController extends Controller
{
    public function index() {
        return view('payment');
    }

    public function checkout(Auction $auction) {

        Stripe::setApiKey(config('stripe.sk'));

        $bid = $auction->bids()->latest()->first();

        $session = Session::create([
            'line_items' => [
                [
                'price_data' => [
                    'currency' => 'gbp',
                    'product_data' => [
                        'name' => $auction->title,
                        'images' => $auction->images()->pluck('path')->toArray(),
                    ],
                    'unit_amount' => $bid->amount,
                ],
                'quantity' => 1,
            ],
        ],
        'mode' => 'payment',
        'success_url' => route('payment.success'),
        'cancel_url' => route('auction.view', $auction),
    ]);

    return redirect()->away($session->url);
    }

    public function success(): View
    {
        return view('payment-success');
    }
}
