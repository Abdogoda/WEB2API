<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->unique()->words($this->faker->numberBetween(1, 3), true);
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $this->faker->boolean(80) ? $this->faker->paragraphs($this->faker->numberBetween(1, 3), true) : null,
            'price' => $this->faker->randomFloat(2, 1, 1000),
            'stock' => $this->faker->numberBetween(0, 100),
            'active' => $this->faker->boolean(90), // 90% chance of being active
            'featured' => $this->faker->boolean(20), // 20% chance of being featured
            'category_id' => Category::factory(),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
