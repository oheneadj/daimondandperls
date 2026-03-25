<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\PaymentGateway;
use App\Enums\PaymentGatewayStatus;
use App\Enums\PaymentMethod;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $booking = Booking::factory()->create();

        return [
            'booking_id' => $booking->id,
            'gateway' => $this->faker->randomElement(PaymentGateway::cases()),
            'method' => $this->faker->randomElement(PaymentMethod::cases()),
            'gateway_reference' => $this->faker->unique()->bothify('??_##########'),
            'amount' => $booking->total_amount,
            'currency' => 'GHS',
            'status' => $this->faker->randomElement(PaymentGatewayStatus::cases()),
            'paid_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
