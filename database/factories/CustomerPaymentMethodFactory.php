<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\PaymentMethod;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CustomerPaymentMethod>
 */
class CustomerPaymentMethodFactory extends Factory
{
    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'type' => PaymentMethod::MobileMoney,
            'label' => 'My MTN MoMo',
            'provider' => '13',
            'account_number' => '024'.fake()->numerify('#######'),
            'account_name' => fake()->name(),
            'is_default' => false,
        ];
    }

    public function default(): static
    {
        return $this->state(fn () => ['is_default' => true]);
    }

    public function card(): static
    {
        return $this->state(fn () => [
            'type' => PaymentMethod::Card,
            'label' => 'My Visa Card',
            'provider' => 'Visa',
            'account_number' => '****'.fake()->numerify('####'),
            'account_name' => fake()->name(),
        ]);
    }

    public function bankTransfer(): static
    {
        return $this->state(fn () => [
            'type' => PaymentMethod::BankTransfer,
            'label' => 'My Bank Account',
            'provider' => 'GCB Bank',
            'account_number' => fake()->numerify('##########'),
            'account_name' => fake()->name(),
        ]);
    }
}
