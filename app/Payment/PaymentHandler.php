<?php

namespace App\Payment;

use http\Client\Request;

class PaymentHandler
{
    public function __construct(protected $paymentGateway) {}

    /**
     * @throws \Exception
     */
    public function pay()
    {
        switch ($this->paymentGateway) {
            case 'stripe':
                $this->paymentGateway = new StripePayment();
                break;
            case 'default':
                throw new \Exception("Payment method not supported");
        }

        return $this->paymentGateway;
    }

    public function processPayment(Request $request): void
    {
        //TODO: Implement payment processing
    }
}
