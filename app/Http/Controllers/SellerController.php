<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Payment\PaymentGateway;
use App\Payment\StripePayment;
use App\Services\Stripe\Seller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Mauricius\LaravelHtmx\Http\HtmxResponseClientRedirect;
use Stripe\StripeClient;

class SellerController extends Controller
{

}
