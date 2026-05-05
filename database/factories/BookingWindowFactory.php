<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\BookingWindow;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BookingWindow>
 */
class BookingWindowFactory extends Factory
{
    public function definition(): array
    {
        $deliveryDay = $this->faker->numberBetween(1, 7);
        // Cutoff is 1–2 days before delivery
        $cutoffDay = (($deliveryDay - 2 - 1 + 7) % 7) + 1;

        return [
            'name' => $this->faker->words(2, true).' Window',
            'delivery_day' => $deliveryDay,
            'cutoff_day' => $cutoffDay,
            'cutoff_time' => '10:00:00',
        ];
    }

    public function friday(): static
    {
        return $this->state([
            'name' => 'Friday Lunch',
            'delivery_day' => 5, // Friday
            'cutoff_day' => 3,   // Wednesday
            'cutoff_time' => '10:00:00',
        ]);
    }

    public function tuesday(): static
    {
        return $this->state([
            'name' => 'Tuesday Delivery',
            'delivery_day' => 2, // Tuesday
            'cutoff_day' => 7,   // Sunday
            'cutoff_time' => '10:00:00',
        ]);
    }
}
