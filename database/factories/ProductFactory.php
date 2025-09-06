<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $adjective = $this->faker->randomElement(['Premium', 'Professional', 'Deluxe', 'Ultra', 'Classic', 'Modern', 'Vintage', 'Essential', 'Advanced', 'Basic']);
        $productType = $this->faker->randomElement(['Widget', 'Gadget', 'Tool', 'Device', 'Accessory', 'Equipment', 'Item', 'Product', 'Kit', 'Set']);
        $model = strtoupper($this->faker->bothify('??##'));
        $name = $adjective . ' ' . $this->faker->company() . ' ' . $productType . ' ' . $model;
        $price = $this->faker->randomFloat(2, 10, 500);
        $salePrice = $this->faker->boolean(30) ? $price * $this->faker->randomFloat(2, 0.7, 0.9) : null;
        $costPrice = $price * $this->faker->randomFloat(2, 0.3, 0.7);
        $stockQuantity = $this->faker->numberBetween(0, 100);
        
        return [
            'sku' => strtoupper($this->faker->unique()->bothify('???-#####')),
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $this->faker->paragraphs(3, true),
            'short_description' => $this->faker->paragraph(),
            'price' => $price,
            'cost_price' => $costPrice,
            'sale_price' => $salePrice,
            'stock_quantity' => $stockQuantity,
            'low_stock_threshold' => $this->faker->numberBetween(5, 20),
            'track_quantity' => $this->faker->boolean(90),
            'in_stock' => $stockQuantity > 0,
            'images' => [
                'products/' . $this->faker->numberBetween(1, 30) . '.jpg',
                'products/' . $this->faker->numberBetween(1, 30) . '.jpg',
            ],
            'attributes' => [
                'brand' => $this->faker->company(),
                'material' => $this->faker->randomElement(['Cotton', 'Polyester', 'Leather', 'Metal', 'Plastic', 'Wood']),
                'color' => $this->faker->safeColorName(),
                'size' => $this->faker->randomElement(['XS', 'S', 'M', 'L', 'XL', 'XXL']),
            ],
            'weight' => $this->faker->randomFloat(2, 0.1, 10) . 'kg',
            'dimensions' => $this->faker->randomFloat(2, 10, 100) . 'x' . $this->faker->randomFloat(2, 10, 100) . 'x' . $this->faker->randomFloat(2, 10, 100) . 'cm',
            'is_active' => $this->faker->boolean(90),
            'is_featured' => $this->faker->boolean(20),
            'views' => $this->faker->numberBetween(0, 1000),
            'sales_count' => $this->faker->numberBetween(0, 100),
        ];
    }

    /**
     * Indicate that the product is out of stock.
     */
    public function outOfStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock_quantity' => 0,
            'in_stock' => false,
        ]);
    }

    /**
     * Indicate that the product is featured.
     */
    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
            'views' => $this->faker->numberBetween(500, 5000),
        ]);
    }

    /**
     * Indicate that the product is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
