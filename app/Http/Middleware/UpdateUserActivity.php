<?php

namespace App\Http\Middleware;

use App\Models\Watcher;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UpdateUserActivity
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (auth()->check() && $request->route()->getName() == 'auction.view') {
            $userId = auth()->id();
            $auctionId = $request->route('auction')->id;

            $watcher = Watcher::where('user_id', $userId)
                ->where('auction_id', $auctionId)
                ->first();

            if (!$watcher) {
                Watcher::create([
                    'user_id' => $userId,
                    'auction_id' => $auctionId,
                    'last_activity_time' => now()
                ]);

            }
            $watcher->update(['last_activity_time' => now()]);
        }

        return $response;
    }
}
