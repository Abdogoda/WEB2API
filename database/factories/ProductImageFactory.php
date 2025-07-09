<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductImage>
 */
class ProductImageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'path' => $this->faker->imageUrl(640, 480, 'products', true, 'Product Image'),
            'is_primary' => $this->faker->boolean(50), // 50% chance of being primary
        ];
    }
}
