<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'body' => $this->faker->paragraph(),
            'user_id' => User::all()->random()->id,
            'thread_id' => Thread::all()->random()->id,
        ];
    }
}
