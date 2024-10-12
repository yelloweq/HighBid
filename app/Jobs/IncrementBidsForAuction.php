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

    private $auction;
    /**
     * Create a new job instance.
     */
    public function __construct(Auction $auction)
    {
        $this->auction = $auction;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->processBid();
    }


    private function processBid($currentHighestBid = null)
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

            DB::commit(); // Commit the transaction to save the update

            // Call processBid recursively with the updated highest bid
            $this->processBid($highestAutobid);
        } catch (Exception $e) {
            DB::rollBack(); // Rollback the transaction on error
            Log::error("An error occurred while processing bids for Auction ID: {$this->auction->id}: " . $e->getMessage());
        }
    }
}
