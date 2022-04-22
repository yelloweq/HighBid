<?php

namespace App\Http\Controllers;

use App\Services\Stripe\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Mauricius\LaravelHtmx\Http\HtmxResponseClientRedirect;

class CustomerController extends Controller
{
    public function form()
    {
        return view('stripe.form');
    }

    public function save(Request $request)
    {
        $this->validate($request, [
            'stripeToken' => 'required'
        ]);
        $user = Auth::user();

        Customer::save($user, $request->stripeToken);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'redirectUrl' => route('auctions')
            ]);
        } else {
            return redirect()->route('auctions');
        }
    }
}
