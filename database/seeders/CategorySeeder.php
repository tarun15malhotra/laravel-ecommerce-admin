<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Electronics',
                'description' => 'Electronic devices and accessories',
                'children' => [
                    'Smartphones',
                    'Laptops',
                    'Tablets',
                    'Headphones',
                    'Smart Watches',
                    'Cameras',
                    'Gaming Consoles',
                    'Accessories'
                ]
            ],
            [
                'name' => 'Clothing',
                'description' => 'Fashion and apparel for all',
                'children' => [
                    'Men\'s Clothing',
                    'Women\'s Clothing',
                    'Kids\' Clothing',
                    'Shoes',
                    'Bags',
                    'Accessories'
                ]
            ],
            [
                'name' => 'Home & Garden',
                'description' => 'Everything for your home and garden',
                'children' => [
                    'Furniture',
                    'Kitchen & Dining',
                    'Bedding',
                    'Bath',
                    'Home Decor',
                    'Garden Tools',
                    'Outdoor Furniture'
                ]
            ],
            [
                'name' => 'Books',
                'description' => 'Books, eBooks, and audiobooks',
                'children' => [
                    'Fiction',
                    'Non-Fiction',
                    'Children\'s Books',
                    'Academic',
                    'Comics & Graphic Novels',
                    'Self-Help'
                ]
            ],
            [
                'name' => 'Sports & Outdoors',
                'description' => 'Sports equipment and outdoor gear',
                'children' => [
                    'Exercise & Fitness',
                    'Outdoor Recreation',
                    'Sports Equipment',
                    'Athletic Clothing',
                    'Camping & Hiking',
                    'Cycling'
                ]
            ],
            [
                'name' => 'Beauty & Personal Care',
                'description' => 'Beauty products and personal care items',
                'children' => [
                    'Skin Care',
                    'Hair Care',
                    'Makeup',
                    'Fragrances',
                    'Personal Care',
                    'Men\'s Grooming'
                ]
            ],
            [
                'name' => 'Toys & Games',
                'description' => 'Toys, games, and entertainment',
                'children' => [
                    'Action Figures',
                    'Board Games',
                    'Dolls',
                    'Educational Toys',
                    'Puzzles',
                    'Video Games'
                ]
            ],
            [
                'name' => 'Food & Grocery',
                'description' => 'Food, beverages, and grocery items',
                'children' => [
                    'Snacks',
                    'Beverages',
                    'Organic Food',
                    'Canned Goods',
                    'Breakfast Foods',
                    'Condiments'
                ]
            ]
        ];

        foreach ($categories as $index => $categoryData) {
            $parent = Category::create([
                'name' => $categoryData['name'],
                'slug' => Str::slug($categoryData['name']),
                'description' => $categoryData['description'],
                'image' => 'categories/' . Str::slug($categoryData['name']) . '.jpg',
                'parent_id' => null,
                'sort_order' => ($index + 1) * 10,
                'is_active' => true,
            ]);

            foreach ($categoryData['children'] as $childIndex => $childName) {
                Category::create([
                    'name' => $childName,
                    'slug' => Str::slug($categoryData['name'] . '-' . $childName),
                    'description' => $childName . ' in ' . $categoryData['name'],
                    'image' => null,
                    'parent_id' => $parent->id,
                    'sort_order' => ($childIndex + 1) * 10,
                    'is_active' => true,
                ]);
            }
        }

        // Create a few inactive categories for testing
        Category::factory(3)->inactive()->create();
    }
}
