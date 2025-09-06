<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class InventoryReportExport implements WithMultipleSheets
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function sheets(): array
    {
        return [
            new LowStockSheet($this->data['lowStockProducts']),
            new OutOfStockSheet($this->data['outOfStockProducts']),
            new StockByCategorySheet($this->data['stockByCategory']),
            new StockMovementSheet($this->data['stockMovement']),
        ];
    }
}

class LowStockSheet implements FromArray, WithTitle, WithHeadings, ShouldAutoSize
{
    protected $products;

    public function __construct($products)
    {
        $this->products = $products;
    }

    public function array(): array
    {
        $rows = [];
        
        foreach ($this->products as $product) {
            $rows[] = [
                $product->id,
                $product->sku,
                $product->name,
                $product->stock_quantity,
                number_format($product->price, 2),
                $product->is_active ? 'Active' : 'Inactive'
            ];
        }

        return $rows;
    }

    public function headings(): array
    {
        return ['ID', 'SKU', 'Product Name', 'Stock Quantity', 'Price', 'Status'];
    }

    public function title(): string
    {
        return 'Low Stock Products';
    }
}

class OutOfStockSheet implements FromArray, WithTitle, WithHeadings, ShouldAutoSize
{
    protected $products;

    public function __construct($products)
    {
        $this->products = $products;
    }

    public function array(): array
    {
        $rows = [];
        
        foreach ($this->products as $product) {
            $rows[] = [
                $product->id,
                $product->sku,
                $product->name,
                number_format($product->price, 2),
                $product->updated_at->format('Y-m-d H:i:s')
            ];
        }

        return $rows;
    }

    public function headings(): array
    {
        return ['ID', 'SKU', 'Product Name', 'Price', 'Last Updated'];
    }

    public function title(): string
    {
        return 'Out of Stock Products';
    }
}

class StockByCategorySheet implements FromArray, WithTitle, WithHeadings, ShouldAutoSize
{
    protected $categories;

    public function __construct($categories)
    {
        $this->categories = $categories;
    }

    public function array(): array
    {
        $rows = [];
        
        foreach ($this->categories as $category) {
            $rows[] = [
                $category->category_name,
                $category->product_count,
                $category->total_stock,
                number_format($category->stock_value, 2)
            ];
        }

        return $rows;
    }

    public function headings(): array
    {
        return ['Category', 'Product Count', 'Total Stock', 'Stock Value'];
    }

    public function title(): string
    {
        return 'Stock by Category';
    }
}

class StockMovementSheet implements FromArray, WithTitle, WithHeadings, ShouldAutoSize
{
    protected $movement;

    public function __construct($movement)
    {
        $this->movement = $movement;
    }

    public function array(): array
    {
        $rows = [];
        
        foreach ($this->movement as $item) {
            $rows[] = [
                $item->id,
                $item->sku,
                $item->name,
                $item->quantity_sold,
                $item->current_stock,
                $item->current_stock > 0 ? round($item->quantity_sold / ($item->quantity_sold + $item->current_stock) * 100, 2) . '%' : '100%'
            ];
        }

        return $rows;
    }

    public function headings(): array
    {
        return ['Product ID', 'SKU', 'Product Name', 'Quantity Sold (30 days)', 'Current Stock', 'Sell-through Rate'];
    }

    public function title(): string
    {
        return 'Stock Movement';
    }
}
