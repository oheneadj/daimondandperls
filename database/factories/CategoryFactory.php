<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $name = $this->faker->unique()->words(2, true),
            'slug' => \Illuminate\Support\Str::slug($name),
            'booking_window_enabled' => false,
        ];
    }

    /**
     * Category with booking window: cutoff Tuesday 06:00, delivery Wednesday (ISO 2 and 3).
     */
    public function withBookingWindow(int $deliveryDay = 3, int $cutoffDay = 2, string $cutoffTime = '06:00:00'): static
    {
        return $this->state([
            'booking_window_enabled' => true,
            'delivery_day' => $deliveryDay,
            'cutoff_day' => $cutoffDay,
            'cutoff_time' => $cutoffTime,
        ]);
    }
}
