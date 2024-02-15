<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessAuctionImageData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $images;
    /**
     * Create a new job instance.
     */
    public function __construct(array $images)
    {
        $this->images = $images;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        foreach ($this->images as $image) {
            // Process the image data
        }
    }
}
