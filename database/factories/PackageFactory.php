<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Package;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Package>
 */
class PackageFactory extends Factory
{
    protected $model = Package::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->words(2, true);
        $features = [
            'Main Course Selection',
            'Side Dish Variety',
            'Fresh Garden Salads',
            'Traditional Desserts',
            'Chilled Local Drinks',
            'Professional Servers',
            'Premium Cutlery & Setup',
            'Eco-friendly Packaging',
        ];

        return [
            'name' => Str::title($name),
            'slug' => Str::slug($name),
            'description' => $this->faker->paragraph(2),
            'price' => $this->faker->randomFloat(2, 50, 500),
            'serving_size' => $this->faker->randomElement(['Serves 10-20', 'Serves 30-50', 'Serves 50-100']),
            'min_guests' => $this->faker->randomElement([20, 50, 100, 150]),
            'features' => $this->faker->randomElements($features, 5),
            'is_active' => true,
            'is_popular' => $this->faker->boolean(20),
            'sort_order' => $this->faker->numberBetween(1, 100),
        ];
    }
}
