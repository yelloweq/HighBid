<?php

namespace App\Jobs;

use App\Models\Auction;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class IncrementBidsForAuction implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected Auction $auction)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->processBid();
    }


    /**
     * Recursive function to increment user's auto-bid based on dynamic bid increments
     * @param $currentHighestBid
     * @return void
     */
    private function processBid($currentHighestBid = null): void
    {
        try {
            DB::beginTransaction();

            if ($currentHighestBid === null) {
                $currentHighestBid = $this->auction->getCurrentHighestBid();
            }

            $highestAutobid = $this->auction->bids()
                ->where('auto_bid', true)
                ->where('id', '!=', $currentHighestBid->id)
                ->where('amount', '>', $currentHighestBid->current_amount)
                ->orderByDesc('amount')
                ->lockForUpdate()
                ->first();

            if (!$highestAutobid) {
                Log::info("No autobid found that can outbid the current highest bid for Auction ID: {$this->auction->id}");
                DB::commit();
                return;
            }

            if ($highestAutobid->user_id == $currentHighestBid->user_id) {
                Log::info("The highest autobid is by the current highest bidder for Auction ID: {$this->auction->id}");
                DB::commit();
                return;
            }

            $bidIncrementInPence = $this->auction->getBidIncrement();
            $newBidAmount = min($highestAutobid->amount, $currentHighestBid->current_amount + $bidIncrementInPence);

            $highestAutobid->update(['current_amount' => $newBidAmount]);
            Log::info("Incremented bid for auction {$this->auction->id} to {$newBidAmount}");

            DB::commit();

            $this->processBid($highestAutobid);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("An error occurred while processing bids for Auction ID: {$this->auction->id}: " . $e->getMessage());
        }
    }
}
