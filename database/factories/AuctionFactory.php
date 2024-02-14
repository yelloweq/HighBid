<?php

namespace Database\Factories;

use App\Enums\AuctionStatus;
use App\Enums\AuctionType;
use App\Enums\DeliveryType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Auction>
 */
class AuctionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->words(3, true),
            'description' => fake()->sentence(),
            'features' => fake()->words(5, true),
            'type' => fake()->randomElement(AuctionType::cases()),
            'price' => fake()->randomNumber(5, false),
            'delivery_type' => fake()->randomElement(DeliveryType::cases()),
            'winner_id' => null,
            'seller_id' => User::all()->random()->id,
            'start_time' => fake()->dateTimeBetween('-1 month', 'now'),
            'end_time' => fake()->dateTimeBetween('now', '+1 month'),
        ];
    }
    public function withStatus(string $status): self
    {
        return $this->state(fn (array $attributes) => [
            'status' => $status,
        ]);
    }
}
