<?php

namespace App\Payment;

use App\Models\Auction;
use App\Models\User;

interface PaymentGatewayInterface
{
    public function checkout(Auction $auction);
    public function createSellerAccount(User $seller);
}
