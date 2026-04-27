<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ContactMessage>
 */
class ContactMessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' => '0244'.fake()->numerify('######'),
            'inquiry_type' => fake()->randomElement(['General Inquiry', 'Pricing', 'Custom Order', 'Event Planning', 'Feedback', 'Other']),
            'message' => fake()->paragraph(),
            'status' => 'new',
            'response_notes' => null,
            'responded_at' => null,
            'responded_by_id' => null,
            'ip_address' => fake()->ipv4(),
        ];
    }
}
