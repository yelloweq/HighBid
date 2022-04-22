<?php

namespace App\Http\Controllers;

use App\Jobs\SendPaymentToSeller;
use App\Models\Auction;
use App\Models\User;
use App\Services\Stripe\Seller;
use App\Services\Stripe\Transaction;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session as FacadesSession;
use Mauricius\LaravelHtmx\Http\HtmxResponseClientRedirect;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Stripe\StripeClient;

use function PHPUnit\Framework\isNull;

class StripeController extends Controller
{
    public function index()
    {
        return view('payment');
    }

    public function createStripeAccount()
    {
        $stripe = new StripeClient(config('stripe.sk'));

        $user = Auth::user();
        if (!is_null($user->stripe_connect_id)) {
            return new HtmxResponseClientRedirect(route('stripe.login'));
        }
        $session = \request()->session()->getId();
        $url = config('services.stripe.connect') . $session;
        return new HtmxResponseClientRedirect($url);
    }

    public function save(Request $request)
    {
        $this->validate($request, [
            'code' => 'required',
            'state' => 'required',
        ]);

        $session = FacadesSession::where('id', $request->state)->first();
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


    public function createStripeAccountLink($stripeAccountId)
    {
        $stripe = new StripeClient(config('stripe.sk'));
        try {
            $stripe->accountLinks->create([
                'account' => $stripeAccountId,
                'refresh_url' => route('stripe.refresh'),
                'return_url' => route('stripe.return'),
                'type' => 'account_onboarding',
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating stripe account link: ' . $e->getMessage());
        }
    }

    public function checkout(Auction $auction)
    {
        Stripe::setApiKey(config('stripe.sk'));

        $bid = $auction->getCurrentHighestBid();

        if (!$bid) {
            return redirect()->back()->with('error', 'No bids on this auction');
        }
        $user = $auction->winner()->first();
        Transaction::create($user, $auction);

        return redirect()->away((route('payment.success', $auction)));
    }

    public function success(Auction $auction): View
    {

        SendPaymentToSeller::dispatch($auction);
        return view('payment-success');
    }
}
