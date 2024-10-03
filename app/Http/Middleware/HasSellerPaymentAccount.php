<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mauricius\LaravelHtmx\Http\HtmxResponseClientRedirect;
use Symfony\Component\HttpFoundation\Response;

class HasSellerPaymentAccount
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): HtmxResponseClientRedirect
    {
        $user = Auth::user();
        if (is_null($user->stripe_account_id)) {
            return new HtmxResponseClientRedirect(route('create.express'));
        }

        return $next($request);
    }
}
