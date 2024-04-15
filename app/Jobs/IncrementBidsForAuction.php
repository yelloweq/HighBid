<?php

namespace App\Jobs;

use App\Models\Auction;
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

    // private function processBid()
    // {
    //     $highestAutobid = $this->auction->bids()
    //         ->where('auto_bid', true)
    //         ->orderByDesc('amount')
    //         ->first();

    //     if (!$highestAutobid) {
    //         Log::error("No highest autobid found for Auction ID: {$this->auction->id}");
    //         return;
    //     }

    //     if ($highestAutobid->current_amount >= $highestAutobid->amount) {
    //         Log::info("Highest autobid is already at max for Auction ID: {$this->auction->id}");
    //         return;
    //     }

    //     $currentHighestBidder = $this->auction->getCurrentHighestBidder();

    //     if (!$currentHighestBidder) {
    //         Log::error("No current highest bidder found for Auction ID: {$this->auction->id}");
    //         return;
    //     }

    //     $isCurrentHighestBidder = $currentHighestBidder->id == $highestAutobid->user_id;

    //     if ($isCurrentHighestBidder) {
    //         Log::info("Current highest bidder is also the highest autobidder for Auction ID: {$this->auction->id}");
    //         return;
    //     }

    //     $bidIncrementInPence = $this->auction->getBidIncrement();
    //     $currentHighestBidInPence = $this->auction->getCurrentHighestBid()->current_amount;


    //     $canBeIncremented = ($currentHighestBidInPence + $bidIncrementInPence) <= $highestAutobid->amount;

    //     if ($canBeIncremented) {
    //         $highestAutobid->update(['current_amount' => $currentHighestBidInPence + $bidIncrementInPence]);

    //         Log::info('1:Incremented bid for auction ' . $this->auction->id . ' to ' . $highestAutobid->current_amount);

    //         $this->processBid();
    //     } elseif ($highestAutobid->amount > $highestAutobid->current_amount) {
    //         $highestAutobid->update(['current_amount' => $highestAutobid->amount]);

    //         Log::info('2:Incremented to MAX bid for auction ' . $this->auction->id . ' to ' . $highestAutobid->current_amount);

    //         $this->processBid();
    //     }
    // }

    private function processBid($currentHighestBid = null)
    {
        try {
            // Start a database transaction
            DB::beginTransaction();

            if ($currentHighestBid === null) {
                $currentHighestBid = $this->auction->getCurrentHighestBid(); // Assume this returns the bid with the highest `current_amount`
            }

            $highestAutobid = $this->auction->bids()
                ->where('auto_bid', true)
                ->where('id', '!=', $currentHighestBid->id)
                ->where('amount', '>', $currentHighestBid->current_amount)
                ->orderByDesc('amount')
                ->lockForUpdate() // Pessimistic locking
                ->first();

            if (!$highestAutobid) {
                Log::info("No autobid found that can outbid the current highest bid for Auction ID: {$this->auction->id}");
                DB::commit(); // Nothing to update, commit transaction
                return; // Exit recursion
            }

            if ($highestAutobid->user_id == $currentHighestBid->user_id) {
                Log::info("The highest autobid is by the current highest bidder for Auction ID: {$this->auction->id}");
                DB::commit(); // Nothing to update, commit transaction
                return; // Exit recursion
            }

            $bidIncrementInPence = $this->auction->getBidIncrement();
            $newBidAmount = min($highestAutobid->amount, $currentHighestBid->current_amount + $bidIncrementInPence);

            $highestAutobid->update(['current_amount' => $newBidAmount]);
            Log::info("Incremented bid for auction {$this->auction->id} to {$newBidAmount}");

            DB::commit(); // Commit the transaction to save the update

            // Call processBid recursively with the updated highest bid
            $this->processBid($highestAutobid);
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback the transaction on error
            Log::error("An error occurred while processing bids for Auction ID: {$this->auction->id}: " . $e->getMessage());
        }
    }
}
