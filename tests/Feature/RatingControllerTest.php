<?php

namespace Tests\Feature;

use App\Http\Controllers\RatingController;
use App\Models\Comment;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mauricius\LaravelHtmx\Http\HtmxResponseClientRedirect;
use Tests\TestCase;

class RatingControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testRedirectsToLoginIfNotAuthenticated()
    {
        Auth::shouldReceive('check')->once()->andReturn(false);
        $controller = new RatingController();

        $response = $controller->createOrUpdate('thread', 1, new Request());

        $this->assertInstanceOf(HtmxResponseClientRedirect::class, $response);
        $this->assertEquals(route('login'), $response->headers->get('HX-Redirect'));
    }

    public function testRejectsInvalidType()
    {
        Auth::shouldReceive('check')->once()->andReturn(true);
        $controller = new RatingController();

        $response = $controller->createOrUpdate('invalidType', 1, new Request($attributes = ['value' => 1]));

        $this->assertEquals(0, $response);
    }

    public function testCreatesOrUpdateRatingForThread()
    {
        Auth::shouldReceive('check')->andReturn(true);
        $user = User::factory()->create();
        $thread = Thread::factory()->create(['user_id' => $user->id]);
        $request = new Request(['value' => 1]);
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        Auth::shouldReceive('user')->andReturn(User::factory()->create());
        $controller = new RatingController();

        $response = $controller->createOrUpdate('thread', $thread->id, $request);

        $this->assertDatabaseHas('ratings', [
            'user_id' => $user->id,
            'value' => $request->value,
        ]);

        $this->assertEquals($thread->getRatingAttribute(), $response);
    }

    public function testCreatesOrUpdateRatingForComment()
    {
        Auth::shouldReceive('check')->andReturn(true);
        $user = User::factory()->create();

        $thread = Thread::factory()->create(['user_id' => $user->id]);
        $comment = Comment::factory()->create(['user_id' => $user->id, 'thread_id' => $thread->id]);
        $request = new Request(['value' => -1]);
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        Auth::shouldReceive('user')->andReturn(User::factory()->create());
        $controller = new RatingController();

        $response = $controller->createOrUpdate('comment', $comment->id, $request);

        $this->assertDatabaseHas('ratings', [
            'user_id' => $user->id,
            'value' => $request->value,
        ]);

        $this->assertEquals($comment->getRatingAttribute(), $response);
    }
}
