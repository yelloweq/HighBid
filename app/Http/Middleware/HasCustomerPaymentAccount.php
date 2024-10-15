<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mauricius\LaravelHtmx\Http\HtmxResponseClientRedirect;
use Symfony\Component\HttpFoundation\Response;

class HasCustomerPaymentAccount
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //TODO: Redirect user to payment gateway onboarding if trying to create an auction without seller account

        return $next($request);
    }
}
