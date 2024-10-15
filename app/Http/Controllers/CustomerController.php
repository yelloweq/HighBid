<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Payment\PaymentGateway;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function checkout(Request $request, Auction $auction, PaymentGateway $paymentGateway): void
    {
        $paymentGateway->checkout($auction);
    }
}
