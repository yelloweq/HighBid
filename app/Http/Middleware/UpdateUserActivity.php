<?php

namespace App\Http\Middleware;

use App\Models\Watcher;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class UpdateUserActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($request->route()->getName() !== 'login' || $request->route()->getName() !== 'register') {
            // Update user activity only if the user is authenticated
            if (auth()->check() && $request->route()->getName() == 'auction.view') {
                $userId = auth()->id();
                $auctionId = $request->route('auction')->id;

                // Find the watcher record for the current user and product
                $watcher = Watcher::where('user_id', $userId)
                    ->where('auction_id', $auctionId)
                    ->first();

                // If watcher record exists, update last activity time, otherwise create a new record
                if ($watcher) {
                    $watcher->update(['last_activity_time' => now()]);
                } else
                    Watcher::create([
                        'user_id' => $userId,
                        'auction_id' => $auctionId,
                        'last_activity_time' => now()
                    ]);
            }
        }

        return $response;
    }
}
