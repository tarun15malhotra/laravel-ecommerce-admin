<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PerformanceReportExport implements WithMultipleSheets
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function sheets(): array
    {
        return [
            new ProductPerformanceSheet($this->data['productPerformance']),
            new CategoryPerformanceSheet($this->data['categoryPerformance']),
        ];
    }
}

class ProductPerformanceSheet implements FromArray, WithTitle, WithHeadings, ShouldAutoSize
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
                number_format($product->cost, 2),
                $product->units_sold,
                number_format($product->revenue, 2),
                number_format($product->cost_of_goods, 2),
                number_format($product->profit, 2),
                $product->revenue > 0 ? round(($product->profit / $product->revenue) * 100, 2) . '%' : '0%'
            ];
        }

        return $rows;
    }

    public function headings(): array
    {
        return ['ID', 'SKU', 'Product Name', 'Price', 'Cost', 'Units Sold', 'Revenue', 'Cost of Goods', 'Profit', 'Margin'];
    }

    public function title(): string
    {
        return 'Product Performance';
    }
}

class CategoryPerformanceSheet implements FromArray, WithTitle, WithHeadings, ShouldAutoSize
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
                $category->id,
                $category->name,
                $category->product_count,
                $category->units_sold,
                number_format($category->revenue, 2)
            ];
        }

        return $rows;
    }

    public function headings(): array
    {
        return ['ID', 'Category Name', 'Product Count', 'Units Sold', 'Revenue'];
    }

    public function title(): string
    {
        return 'Category Performance';
    }
}
