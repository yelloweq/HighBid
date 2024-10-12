<?php

namespace Database\Factories;

use App\Enums\AuctionStatus;
use App\Enums\AuctionType;
use App\Enums\DeliveryType;
use App\Models\Auction;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Auction>
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
            'price' => fake()->randomNumber(6, false),
            'delivery_type' => fake()->randomElement(DeliveryType::cases()),
            'category_id' => Category::factory(),
            'winner_id' => null,
            'seller_id' => User::factory(),
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
