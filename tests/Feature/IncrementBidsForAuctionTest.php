<?php

namespace Tests\Feature;

use App\Helpers\BidIncrementHelper;
use App\Jobs\IncrementBidsForAuction;
use App\Models\Auction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IncrementBidsForAuctionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function auto_bid_is_incremented_after_outbid_by_another_user()
    {
        $auction = Auction::factory()->withStatus('Active')->create(['price' => 10000]);
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post(route('auction.bid', ['auction' => $auction->id]), [
            'bid' => 200,
            'auto_bid' => true,
        ]);

        $response->assertViewHas('message', 'Bid placed successfully');
        $this->assertDatabaseHas('bids', [
            'auction_id' => $auction->id,
            'user_id' => $user->id,
            'current_amount' => $auction->price + BidIncrementHelper::getBidIncrement($auction->price),
            'amount' => 20000,
        ]);
        $otherUser = User::factory()->create();
        $responseOther = $this->actingAs($otherUser)->post(route('auction.bid', ['auction' => $auction->id]), [
            'bid' => 150,
        ]);

        $responseOther->assertViewHas('message', 'Bid placed successfully');
        $this->assertDatabaseHas('bids', [
            'auction_id' => $auction->id,
            'user_id' => $otherUser->id,
            'amount' => 15000,
        ]);

        $job = new IncrementBidsForAuction($auction);
        $job->handle();

        $this->assertDatabaseHas('bids', [
            'auction_id' => $auction->id,
            'user_id' => $user->id,
            'amount' => 20000,
            'current_amount' => 15000 + BidIncrementHelper::getBidIncrement(15000),
        ]);
    }
}
