<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\Stripe\Seller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Mauricius\LaravelHtmx\Http\HtmxResponseClientRedirect;
use Stripe\StripeClient;

class SellerController extends Controller
{
    public function create(): HtmxResponseClientRedirect
    {
        dd("IM HERE");
        $stripe = new StripeClient(config('stripe.sk'));

        dd($stripe);
        $user = User::find(Auth::id());
        if (!is_null($user->stripe_account_id)) {
            return new HtmxResponseClientRedirect(route('payment.login'));
        }

        $connect_account = $stripe->accounts->create(['type' => 'express']);
        $user->update(['stripe_account_id' => $connect_account->id]);
        $user->save();

        return new HtmxResponseClientRedirect(route('login.express'));
    }

    public function save(Request $request): HtmxResponseClientRedirect
    {
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
