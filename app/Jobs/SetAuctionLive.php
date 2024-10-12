<?php

namespace App\Jobs;

use App\Enums\AuctionStatus;
use App\Models\Auction;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SetAuctionLive implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $auctionID;
    /**
     * Create a new job instance.
     */
    public function __construct($auction)
    {
        $this->auctionID = $auction->id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        try {
            $auction = Auction::where('id', $this->auctionID)->first();

            if ($auction->images()->count() == 0) {
                Log::info('Auction has no images, cannot set it live');
                return;
            }
            //if any auction image has flagged = true, then return
            if ($auction->images()->where('flagged', true)->count() > 0) {
                Log::info('Auction has flagged images, cannot set it live');
                return;
            }

            $auction->update(['status' => AuctionStatus::ACTIVE]);
            Log::info('Setting auction live :)');
        } catch (Exception $e) {
            Log::error('Failed to set auction live: ' . $e->getMessage());
        }
    }
}
