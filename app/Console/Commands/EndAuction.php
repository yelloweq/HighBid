<?php

namespace App\Console\Commands;

use App\Enums\AuctionStatus;
use App\Models\Auction;
use App\Notifications\AuctionHasEnded;
use App\Notifications\AuctionWon;
use Carbon\Carbon;
use Illuminate\Console\Command;

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
        $now = Carbon::now();
        $auctions = Auction::where('status', AuctionStatus::ACTIVE)->where('end_time', '<=', $now)->get();

        foreach ($auctions as $auction) {
            $this->updateAuctionStatus($auction);
            $this->notifySeller($auction);
            $this->notifyWinner($auction);
        }
        
        
    }

    protected function updateAuctionStatus(Auction $auction)
    {
        $winner = $auction->bids()->orderBy('amount', 'desc')->first();
        $auction->update(['status' => AuctionStatus::CLOSED, 'winner_id' => $winner->user_id]);
    }

    protected function notifySeller(Auction $auction)
    {
        $seller = $auction->seller()->first();
        $seller->notify(new AuctionHasEnded($auction));
    }

    protected function notifyWinner(Auction $auction)
    {
        $winner = $auction->winner()->first();
        $winner->notify(new AuctionWon($auction));
    }
}
