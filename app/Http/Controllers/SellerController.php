<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\Stripe\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Mauricius\LaravelHtmx\Http\HtmxResponseClientRedirect;
use Stripe\Account;
use Stripe\Stripe;
use Stripe\StripeClient;

class SellerController extends Controller
{
    public function create()
    {
        $stripe = new StripeClient(config('stripe.sk'));

        $user = User::find(Auth::id());
        if (!is_null($user->stripe_account_id)) {
            return new HtmxResponseClientRedirect(route('stripe.login'));
        }
        // $session = \request()->session()->getId();
        // $url = config('services.stripe.connect') . $session;
        // // dd($url);
        // return redirect($url);

        $connect_account = $stripe->accounts->create(['type' => 'express']);
        $user->update(['stripe_account_id' => $connect_account->id]);
        $user->save();

        return redirect(route('login.express'));
    }

    public function save(Request $request)
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

    public function login()
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
