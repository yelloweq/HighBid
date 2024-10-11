<?php

namespace App\Payment;

use App\Models\User;
use Illuminate\Http\Response;
use Mauricius\LaravelHtmx\Http\HtmxResponseClientRedirect;
use Stripe\BaseStripeClient;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;

class StripePayment extends PaymentGateway
{
    protected BaseStripeClient $stripe;
    public function __construct()
    {
        $this->stripe = new StripeClient(config('stripe.sk'));
    }

    /**
     * @throws ApiErrorException
     */
    public function createSellerAccount(User $user, string $accountType): Response
    {
        if (!is_null($user->stripe_account_id)) {
            return new HtmxResponseClientRedirect(route('payment.login'));
        }

        $connect_account = $this->stripe->accounts->create(['type' => 'express']);
        $user->update(['stripe_account_id' => $connect_account->id]);
        $user->save();

        //TODO: this is pretty roundabout way to redirect. should just use back()?
        return new HtmxResponseClientRedirect(back()->getTargetUrl());
    }
    public function pay($amount)
    {
        //TODO: Implement stripe payment
    }

}
