<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class ProductsImport implements ToModel, WithHeadingRow, WithValidation, WithBatchInserts, WithChunkReading
{
    public function model(array $row)
    {
        $product = Product::updateOrCreate(
            ['sku' => $row['sku']],
            [
                'name' => $row['name'],
                'slug' => Str::slug($row['name']),
                'description' => $row['description'],
                'short_description' => $row['short_description'] ?? null,
                'price' => $row['price'],
                'compare_at_price' => $row['compare_at_price'] ?? null,
                'cost' => $row['cost'] ?? 0,
                'stock_quantity' => $row['stock_quantity'] ?? 0,
                'in_stock' => ($row['in_stock'] ?? 'yes') === 'yes',
                'weight' => $row['weight'] ?? null,
                'length' => $row['length'] ?? null,
                'width' => $row['width'] ?? null,
                'height' => $row['height'] ?? null,
                'is_active' => ($row['status'] ?? 'active') === 'active',
                'is_featured' => ($row['featured'] ?? 'no') === 'yes',
            ]
        );

        // Handle categories
        if (!empty($row['categories'])) {
            $categoryNames = explode(',', $row['categories']);
            $categoryIds = [];
            
            foreach ($categoryNames as $categoryName) {
                $categoryName = trim($categoryName);
                $category = Category::firstOrCreate(
                    ['name' => $categoryName],
                    ['slug' => Str::slug($categoryName), 'is_active' => true]
                );
                $categoryIds[] = $category->id;
            }
            
            $product->categories()->sync($categoryIds);
        }

        // Handle tags
        if (!empty($row['tags'])) {
            $tagNames = explode(',', $row['tags']);
            $tagIds = [];
            
            foreach ($tagNames as $tagName) {
                $tagName = trim($tagName);
                $tag = Tag::firstOrCreate(
                    ['name' => $tagName],
                    ['slug' => Str::slug($tagName)]
                );
                $tagIds[] = $tag->id;
            }
            
            $product->tags()->sync($tagIds);
        }

        return $product;
    }

    public function rules(): array
    {
        return [
            'sku' => 'required|string',
            'name' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'nullable|integer|min:0',
        ];
    }

    public function batchSize(): int
    {
        return 100;
    }

    public function chunkSize(): int
    {
        return 100;
    }
}
