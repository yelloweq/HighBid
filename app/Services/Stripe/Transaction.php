<?php

namespace App\Services\Stripe;

use App\Enums\AuctionStatus;
use App\Models\Auction;
use App\Models\Payment;
use App\Models\User;
use Database\Seeders\AuctionSeeder;
use Stripe\Charge;
use Stripe\Stripe;
use Stripe\Transfer;

class Transaction
{
    public static function create(User $user, Auction $auction): void
    {
        $amount = $auction->getCurrentHighestBid()?->current_amount ?? $auction->price;

        $payout = (int) $amount * config('payment.payment-processor.'.config('payment.payment-processor.default').'.');

        Stripe::setApiKey(env('STRIPE_SK'));

        $charge = Charge::create([
            'amount' => $amount,
            'currency' => 'gbp',
            'customer' => $user->stripe_customer_id,
            'description' => $auction->title,
        ]);

        Transfer::create([
            'amount' => $payout,
            'currency' => 'gbp',
            'destination' => $auction->seller->stripe_account_id,
            'source_transaction' => $charge->id,
        ]);

        Payment::create([
            'customer_id' => $user->id,
            'auction_id' => $auction->id,
            'stripe_charge_id' => $charge->id,
            'paid_out' => $payout,
            'fees_collected' => $amount - $payout,
        ]);

        $auction->update([
            'status' => AuctionStatus::CLOSED,
        ]);
    }
}
