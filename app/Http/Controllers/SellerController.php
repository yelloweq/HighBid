<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateConnectAccountRequest;
use App\Payment\PaymentGateway;
use Illuminate\Http\RedirectResponse;

class SellerController extends Controller
{
    public function createConnectAccount(CreateConnectAccountRequest $request, PaymentGateway $paymentGateway): RedirectResponse
    {
        $onboardingUrl = $paymentGateway->createSellerAccount($request->user());

        if (!empty($onboardingUrl))
        {
            return redirect($onboardingUrl);
        }

        //TODO: Create route and view
        return redirect(route('payment.failed-to-create-seller-account'));
    }
}
