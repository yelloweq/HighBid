<?php

namespace Tests\Feature;

use App\Enums\AuctionType;
use App\Enums\DeliveryType;
use App\Helpers\BidIncrementHelper;
use App\Http\Controllers\AuctionController;
use App\Jobs\IncrementBidsForAuction;
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
            'end-time' => Carbon::now()->addDays(10)->toDateTimeString(),
        ]);
        $response->assertStatus(200);
        $this->assertEquals(route('auction.view', ['auction' => 1]), $response->headers->get('HX-Redirect'));

        $this->assertDatabaseHas('auctions', [
            'title' => 'Unique Title',
            'seller_id' => $user->id
        ]);
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
        $otherUser = User::factory()->create([
            'stripe_customer_id' => 'cus_123',
        ]);
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
}
