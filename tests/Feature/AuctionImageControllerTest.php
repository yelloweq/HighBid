<?php

namespace Tests\Feature;

use App\Http\Controllers\AuctionImageController;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mockery;

class AuctionImageControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    public function setUp(): void
    {
        parent::setUp();
        // Set up fake storage
        Storage::fake('public');
        DB::beginTransaction();
    }
    public function tearDown(): void
    {
        DB::rollBack();
        Mockery::close();
        parent::tearDown();
    }


    /** @test */
    public function it_requires_a_file_and_image_matching_key()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->json('POST', '/upload/images');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['file', 'imageMatchingKey']);
    }


    /** @test */
    public function it_stores_images_successfully()
    {
        $user = User::factory()->create();
        $file = UploadedFile::fake()->image('photo1.jpg');

        $response = $this->actingAs($user)->json('POST', '/upload/images', [
            'file' => [$file],
            'imageMatchingKey' => 'test-key',
        ]);

        $response->assertStatus(200)
            ->assertJson(['success' => 'uploaded successfully']);

        // Assert the file was stored
        Storage::disk('public')->exists('uploads/' . md5_file($file->getRealPath()) . '.jpg');

        // Assert the database has the record
        $this->assertDatabaseHas('auction_images', [
            'path' => 'uploads/' . md5_file($file->getRealPath()) . '.jpg',
            'image_matching_key' => 'test-key',
            'user_id' => $user->id,
        ]);
    }

    /** @test */
    public function it_handles_exceptions_during_upload()
    {
        $fileMock = $this->createMock(UploadedFile::class);
        $fileMock->method('getClientOriginalName')->willReturn('test.jpg');
        $fileMock->method('getClientOriginalExtension')->willReturn('jpg');
        $fileMock->method('getRealPath')->will($this->throwException(new Exception('Simulated error')));

        $request = Request::create('/upload/images', 'POST', [
            'imageMatchingKey' => 'test_key',
        ]);


        $request->files->set('file', [$fileMock]);

        Storage::shouldReceive('path')->andReturn('fake/directory/path');

        $controller = new AuctionImageController();

        $response = $controller->store($request);

        $this->assertEquals(500, $response->getStatusCode(), "Expected status code 500 on exception.");
        $this->assertJson($response->getContent());
        $this->assertEquals(['error' => 'Upload failed. Please try again later.'], $response->getData(true));
    }
}
