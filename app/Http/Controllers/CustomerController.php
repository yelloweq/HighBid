<?php

namespace App\Http\Controllers;

use App\Services\Stripe\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class CustomerController extends Controller
{
    public function form(): View
    {
        return view('stripe.form');
    }

    /**
     * @throws ValidationException
     */
    public function save(Request $request): RedirectResponse|JsonResponse
    {
        $this->validate($request, [
            'stripeToken' => 'required'
        ]);

        $user = Auth::user();

        if (!$user) {
            //TODO: Handle no user for credit card save action
            Log::error("CustomerController::save: user is not logged in");
        }
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
