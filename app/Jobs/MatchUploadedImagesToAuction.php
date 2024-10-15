<?php

namespace App\Jobs;

use App\Enums\AuctionStatus;
use App\Models\Auction;
use App\Models\AuctionImage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class MatchUploadedImagesToAuction implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected Auction $auction, protected string $imageMatchingKey)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('Matching images to auction');
        AuctionImage::where('image_matching_key', $this->imageMatchingKey)
            ->update(['auction_id' => $this->auction->id]);
    }
}
