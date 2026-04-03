<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\BookingStatus;
use App\Enums\BookingType;
use App\Enums\EventType;
use App\Enums\PaymentStatus;
use App\Models\Booking;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    protected $model = Booking::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'reference' => 'CAT-'.now()->year.'-'.$this->faker->unique()->numerify('#####'),
            'booking_type' => BookingType::Meal,
            'customer_id' => Customer::factory(),
            'event_date' => $this->faker->dateTimeBetween('now', '+3 months')->format('Y-m-d'),
            'event_start_time' => '10:00:00',
            'event_end_time' => '14:00:00',
            'event_type' => $this->faker->randomElement(EventType::cases()),
            'pax' => $this->faker->numberBetween(10, 200),
            'is_buffet' => false,
            'total_amount' => $this->faker->randomFloat(2, 50, 1000),
            'status' => $this->faker->randomElement(BookingStatus::cases()),
            'payment_status' => $this->faker->randomElement(PaymentStatus::cases()),
        ];
    }

    public function meal(): static
    {
        return $this->state(fn (array $attributes): array => [
            'booking_type' => BookingType::Meal,
        ]);
    }

    public function event(): static
    {
        return $this->state(fn (array $attributes): array => [
            'booking_type' => BookingType::Event,
            'total_amount' => 0,
        ]);
    }

    public function buffet(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_buffet' => true,
        ]);
    }
}
