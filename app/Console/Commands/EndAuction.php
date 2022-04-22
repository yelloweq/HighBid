<?php

namespace App\Console\Commands;

use App\Enums\AuctionStatus;
use App\Models\Auction;
use App\Notifications\AuctionHasEnded;
use App\Notifications\AuctionWon;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EndAuction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auctions:end';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Go over auctions that have finished, update their status and notify the seller and winner.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now()->timezone('Europe/London');
        $auctionIds = Auction::with('bids', 'seller')
            ->where('status', AuctionStatus::ACTIVE)
            ->where('end_time', '<=', $now)
            ->pluck('id');

        Log::info("auctions to end: " . $auctionIds->count());

        foreach ($auctionIds as $auctionId) {
            if (Cache::add("processing_auction_{$auctionId}", true, 120)) {  // Lock for 2 minutes
                $auction = Auction::find($auctionId);
                $this->updateAuctionStatus($auction);
                $this->notifySeller($auction);
                $this->notifyWinner($auction);
                Cache::forget("processing_auction_{$auctionId}");
            } else {
                Log::info("Skipping auction {$auctionId} as it's already being processed.");
            }
        }
    }

    protected function updateAuctionStatus(Auction $auction)
    {
        $winner = $auction->bids()->orderBy('current_amount', 'desc')->first();
        if (!$winner) {
            Log::error("auction {$auction->id}: no winner");
            $auction->update(['status' => AuctionStatus::PROCCESSING]);
            $auction->save();
            return;
        }
        Log::error("auction {$auction->id}: attempting to update status");
        $auction->update(['status' => "Processing", 'winner_id' => $winner->user_id]);
        $auction->save();
        $auction->refresh();
        Log::error("auction {$auction->id} STATUS updated to: {$auction->status}");
    }

    protected function notifySeller(Auction $auction)
    {
        if (!$auction->winner_id) {
            Log::error("auction {$auction->id}: no winner, notifying seller");
            return;
        }
        $seller = $auction->seller()->first();
        $seller->notify(new AuctionHasEnded($auction));
    }

    protected function notifyWinner(Auction $auction)
    {
        if (!$auction->winner_id) {
            return;
        }
        $winner = $auction->winner()->first();
        $winner->notify(new AuctionWon($auction));
    }
}
