<?php

namespace Database\Factories;

use App\Models\Tag;
use App\Models\Thread;
use App\Models\ThreadTag;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ThreadTag>
 */
class ThreadTagFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'thread_id' => Thread::all()->random()->id,
            'tag_id' => Tag::all()->random()->id,
        ];
    }
}
