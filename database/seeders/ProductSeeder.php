<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all categories (excluding parent categories)
        $categories = Category::whereNotNull('parent_id')->get();
        
        // Get all tags
        $tags = Tag::all();

        // Create 100 products
        Product::factory(100)->create()->each(function ($product) use ($categories, $tags) {
            // Attach 1-3 categories to each product
            $product->categories()->attach(
                $categories->random(rand(1, 3))->pluck('id')->toArray()
            );
            
            // Attach 1-5 tags to each product
            $product->tags()->attach(
                $tags->random(rand(1, 5))->pluck('id')->toArray()
            );
        });

        // Create 20 featured products
        Product::factory(20)->featured()->create()->each(function ($product) use ($categories, $tags) {
            $product->categories()->attach(
                $categories->random(rand(1, 2))->pluck('id')->toArray()
            );
            $product->tags()->attach(
                $tags->random(rand(2, 4))->pluck('id')->toArray()
            );
        });

        // Create 10 out of stock products
        Product::factory(10)->outOfStock()->create()->each(function ($product) use ($categories, $tags) {
            $product->categories()->attach(
                $categories->random(rand(1, 2))->pluck('id')->toArray()
            );
            $product->tags()->attach(
                $tags->random(rand(1, 3))->pluck('id')->toArray()
            );
        });

        // Create 5 inactive products for testing
        Product::factory(5)->inactive()->create()->each(function ($product) use ($categories, $tags) {
            $product->categories()->attach(
                $categories->random(1)->pluck('id')->toArray()
            );
        });
    }
}
