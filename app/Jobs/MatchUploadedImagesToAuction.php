<?php

namespace App\Jobs;

use App\Models\Auction;
use App\Models\AuctionImage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MatchUploadedImagesToAuction implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $auction;
    protected $imageMatchingKey;
    /**
     * Create a new job instance.
     */
    public function __construct(Auction $auction, string $imageMatchingKey)
    {
        $this->auction = $auction;
        $this->imageMatchingKey = $imageMatchingKey;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        AuctionImage::where('image_matching_key', $this->imageMatchingKey)
            ->update(['auction_id' => $this->auction->id]);
    }
}
