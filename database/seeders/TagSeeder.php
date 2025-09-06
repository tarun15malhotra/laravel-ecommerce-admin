<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            'New Arrival',
            'Best Seller',
            'Sale',
            'Featured',
            'Limited Edition',
            'Clearance',
            'Trending',
            'Popular',
            'Exclusive',
            'Premium',
            'Budget',
            'Eco-Friendly',
            'Organic',
            'Handmade',
            'Vintage',
            'Modern',
            'Classic',
            'Seasonal',
            'Summer Collection',
            'Winter Collection',
            'Back to School',
            'Holiday Special',
            'Gift Ideas',
            'Recommended',
            'Top Rated',
        ];

        foreach ($tags as $tagName) {
            Tag::create([
                'name' => $tagName,
                'slug' => Str::slug($tagName),
            ]);
        }
    }
}
