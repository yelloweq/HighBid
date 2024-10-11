<?php

namespace App\Http\Controllers;

use App\Exceptions\UserNotFoundException;
use App\Models\User;
use App\Payment\PaymentGateway;
use App\Services\Stripe\Seller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Mauricius\LaravelHtmx\Http\HtmxResponseClientRedirect;
use Stripe\StripeClient;

class PaymentController extends Controller
{
    public function createSellerAccount(Request $request, PaymentGateway $paymentGateway): Response
    {
        try {
            $user = Auth::user();
            if (!$user) {
                throw new UserNotFoundException("User not found, cannot create seller account.");
            }

            $paymentGateway->createSellerAccount($user, 'express');
        }
        catch (\Exception $e) {
            //TODO: show correct error page on stripe api exception
            return new HtmxResponseClientRedirect(route('home'));
        }

        return new HtmxResponseClientRedirect(route('login.express'));
    }

    public function save(Request $request): HtmxResponseClientRedirect
    {
        //TODO: move validation to custom request
        $this->validate($request, [
            'code' => 'required',
            'state' => 'required',
        ]);

        $session = Session::where('id', $request->state)->first();
        if (is_null($session)) {
            return new HtmxResponseClientRedirect(route('auctions'));
        }

        $data = Seller::create($request->code);
        User::find($session->user_id)->update([
            'stripe_connect_id' => $data->stripe_user_id,
            'stripe_account_id' => $data->stripe_account_id,
        ]);

        return new HtmxResponseClientRedirect(route('auctions'));
    }

    public function login(): RedirectResponse
    {
        $user = Auth::user();
        $stripe = new StripeClient(config('stripe.sk'));

        $account_link = $stripe->accountLinks->create([
            'account' => $user->stripe_account_id,
            'refresh_url' => route('auctions'),
            'return_url' => route('auction.create'),
            'type' => 'account_onboarding',
        ]);

        return redirect($account_link->url);
    }
}
