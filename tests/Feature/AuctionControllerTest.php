<?php

namespace Tests\Feature;

use App\Enums\AuctionType;
use App\Enums\DeliveryType;
use App\Helpers\BidIncrementHelper;
use App\Http\Controllers\AuctionController;
use App\Jobs\EndAuction;
use App\Jobs\IncrementBidsForAuction;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mauricius\LaravelHtmx\Http\HtmxResponseClientRedirect;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use App\Models\Auction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Queue;

class AuctionControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();
        // This would typically be in a route service provider or similar:
        $this->app->instance('path.public', __DIR__ . '/public');
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    /** @test */
    public function a_guest_cannot_view_create_auction_form()
    {
        Auth::shouldReceive('check')->once()->andReturn(false);
        $controller = new AuctionController();

        $response = $controller->create(new Request());

        $this->assertInstanceOf(HtmxResponseClientRedirect::class, $response);
        $this->assertEquals(route('login'), $response->headers->get('HX-Redirect'));
    }

    /** @test */
    public function an_authenticated_and_registered_seller_can_view_create_auction_form()
    {
        $user = User::factory()->create([
            'stripe_connect_id' => 'acct_123',
        ]);

        $response = $this->actingAs($user)->get(route('auction.create'));
        $response->assertStatus(200);
        $response->assertViewIs('auction.auction-create');
    }

    /** @test */
    public function an_authenticated_and_registered_seller_can_create_auction()
    {
        $user = User::factory()->create([
            'stripe_connect_id' => 'acct_123',
        ]);
        $response = $this->actingAs($user)->post(route('auction.store'), [
            'title' => 'Unique Title',
            'description' => 'A great description',
            'features' => 'These, Are, Features',
            'auction-type' => "timelimit",
            'price' => 100.00,
            'delivery-type' => "collection",
            'end-date' => Carbon::now()->addDays(10)->toDateTimeString(),
        ]);

        $response->assertOk();
        $this->assertEquals(route('auction.view', ['auction' => 1]), $response->headers->get('HX-Redirect'));

        $this->assertDatabaseHas('auctions', [
            'title' => 'Unique Title',
            'seller_id' => $user->id,
            'price' => 10000
        ]);
    }

    /** @test */
    public function created_auction_dispatches_delayed_end_auction_job()
    {
        Carbon::setTestNow('2024-10-18 18:00:00');
        Queue::fake();
        $user = User::factory()->create([
            'stripe_connect_id' => 'acct_123',
        ]);
        $EndTime = Carbon::now()->addDays(10);
        $this->actingAs($user)->post(route('auction.store'), [
            'title' => 'Unique Title',
            'description' => 'A great description',
            'features' => 'These, Are, Features',
            'auction-type' => "timelimit",
            'price' => 100.00,
            'delivery-type' => "collection",
            'end-date' => $EndTime,
        ]);

        Queue::assertPushed(EndAuction::class, fn($job) => $job->delay->equalTo($EndTime));
    }

    /** @test */
    public function unregistered_seller_visiting_create_auction_form_gets_redirected_to_onboarding()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('auction.create'));
        $response->assertStatus(200);
        $response->assertHeader('hx-redirect', route('payments.createConnectAccount'));
    }

    /** @test */
    public function validate_auction_bid_process()
    {
        Queue::fake();
        $auction = Auction::factory()->withStatus('Active')->create(['price' => 10000]);
        $otherUser = User::factory()->create();
        $response = $this->actingAs($otherUser)->post(route('auction.bid', ['auction' => $auction->id]), [
            'bid' => 120.00
        ]);

        $response->assertViewHas('message', 'Bid placed successfully');
        $this->assertDatabaseHas('bids', [
            'user_id' => $otherUser->id,
            'amount' => 12000
        ]);
        Queue::assertPushed(IncrementBidsForAuction::class, 1);
    }

    /** @test */
    public function new_bids_dispatch_increment_bids_for_auction_job()
    {
        Queue::fake();
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

        /**
         * Job is dispatched twice, because we need to check if any user has an active autobid after every bid made
         * so it can be incremented.
         */
        Queue::assertPushed(IncrementBidsForAuction::class, 1);
    }

    /** @test */
    public function bids_with_autobid_are_correctly_incremented(): void
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

        $this->assertDatabaseHas('bids', [
        'auction_id' => $auction->id,
        'user_id' => $user->id,
        'amount' => 20000,
        'current_amount' => 15000 + BidIncrementHelper::getBidIncrement(15000),
        ]);
    }
}
