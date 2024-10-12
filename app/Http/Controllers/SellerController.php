<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateConnectAccountRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Mauricius\LaravelHtmx\Http\HtmxResponse;
use Mauricius\LaravelHtmx\Http\HtmxResponseClientRedirect;
use Stripe\StripeClient;

class SellerController extends Controller
{
    //Create a connected account
    public function createConnectAccount(CreateConnectAccountRequest $request): Response
    {
        $stripe = new StripeClient(config('services.stripe.secret'));

        try {
            $account = $stripe->accounts->create([
                'type' => 'express',
            ]);

            $request->user()->stripe_connect_id = $account->id;
            $request->user()->save();

            return new HtmxResponseClientRedirect(route('auction.create'));
        }
        catch (Exception $e)
        {
            Log::error("An error occurred when calling the Stripe API to create an account: " . $e->getMessage());
            dd($e->getMessage());
            return new HtmxResponse('<h1>something went wrong</h1>', 500);
//            return new HtmxResponseClientRedirect(route('errors.internal_server_error', ['error' => $e->getMessage()]));
        }
    }

    //create account link endpoint
    public function createAccountLink(Request $request): HtmxResponseClientRedirect
    {
        $stripe = new StripeClient(config('services.stripe.secret'));

        try {
            $connectAccountId = $request->user()->stripe_connect_id;

            $account_link = $stripe->accountLinks->create([
                'account' => $connectAccountId,
                'return_url' => route('stripe.return_url'),
                'refresh_url' => route('stripe.refresh_url'),
                'type' => 'account_onboarding',
            ]);

            return new HtmxResponseClientRedirect($account_link->url);
        }
        catch (Exception $e)
        {
            Log::error('An error occurred when calling the Stripe API to create an account link: ' . $e->getMessage());
            return new HtmxResponseClientRedirect(route('errors.internal_server_error', ['error' => $e->getMessage()]));
        }
    }
}
