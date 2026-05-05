<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Review;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Review>
 */
class ReviewFactory extends Factory
{
    public function definition(): array
    {
        return [
            'booking_id' => Booking::factory(),
            'customer_id' => Customer::factory(),
            'token' => Str::random(60),
            'stars' => null,
            'author_name' => null,
            'reviewer_phone' => null,
            'message' => null,
            'is_approved' => false,
            'points_awarded' => 0,
            'friend_name' => null,
            'friend_phone' => null,
            'friend_sms_sent_at' => null,
            'submitted_at' => null,
        ];
    }

    public function submitted(): static
    {
        return $this->state(fn (array $attributes) => [
            'stars' => $this->faker->numberBetween(1, 5),
            'author_name' => $this->faker->name(),
            'reviewer_phone' => '0'.$this->faker->numerify('#########'),
            'message' => $this->faker->sentence(),
            'is_approved' => true,
            'submitted_at' => now(),
        ]);
    }
}
