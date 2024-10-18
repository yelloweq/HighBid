<?php

namespace App\Jobs;

use App\Enums\AuctionStatus;
use App\Models\Auction;
use App\Notifications\AuctionHasEnded;
use App\Notifications\AuctionWon;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class EndAuction implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected Auction $auction)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->updateAuctionStatus($this->auction);
        $this->notifySeller($this->auction);
        $this->notifyWinner($this->auction);
    }

    protected function updateAuctionStatus(Auction $auction): void
    {
        $winner = $auction->bids()->orderBy('current_amount', 'desc')->first();
        if (!$winner) {
            Log::error("auction {$auction->id}: no winner");
            $auction->update(['status' => AuctionStatus::CLOSED]);
            $auction->save();
            return;
        }
        $auction->update(['status' => AuctionStatus::PROCCESSING, 'winner_id' => $winner->user_id]);
        $auction->save();
        $auction->refresh();
        Log::info("Auction ID: {$auction->id} | STATUS updated to: {$auction->status}");
    }

    protected function notifySeller(Auction $auction): void
    {
        if (!$auction->winner_id) {
            Log::error("Auction ID: {$auction->id} | no winner, notifying seller");
            //TODO: Dispatch job to notify the seller about auction ending with no winner
            return;
        }
        $seller = $auction->seller()->first();

        if (!$seller) {
            Log::error("Auction ID: {$auction->id} | no seller to notify");
        }
        $seller->notify(new AuctionHasEnded($auction));
    }

    protected function notifyWinner(Auction $auction): void
    {
        $winner = $auction->winner()->first();

        if (!$winner) {
            return;
        }

        $winner->notify(new AuctionWon($auction));
    }
}
