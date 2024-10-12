<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mauricius\LaravelHtmx\Http\HtmxResponseClientRedirect;
use \Illuminate\Http\Response;

class HasSellerPaymentAccount
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (is_null($request->user()->stripe_connect_id)) {
            return new HtmxResponseClientRedirect(route('payments.createConnectAccount'));
        }

        return $next($request);
    }
}
