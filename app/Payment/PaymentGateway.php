<?php

namespace App\Payment;

use App\Models\User;
use http\Client\Request;
use Illuminate\Support\Facades\Log;

class PaymentGateway
{
    public function processPayment()
    {
        Log::info("Payment Gateway: processing payment");
    }

    public function createSellerAccount(User $user, string $accountType)
    {
        Log::info("Payment Gateway: create seller account");
    }
}
