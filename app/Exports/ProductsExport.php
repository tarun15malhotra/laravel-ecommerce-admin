<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    public function collection()
    {
        return Product::with(['categories', 'tags'])->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'SKU',
            'Name',
            'Description',
            'Price',
            'Compare Price',
            'Cost',
            'Stock Quantity',
            'In Stock',
            'Categories',
            'Tags',
            'Weight',
            'Length',
            'Width',
            'Height',
            'Status',
            'Featured',
            'View Count',
            'Created At',
            'Updated At'
        ];
    }

    public function map($product): array
    {
        return [
            $product->id,
            $product->sku,
            $product->name,
            $product->description,
            $product->price,
            $product->compare_at_price,
            $product->cost,
            $product->stock_quantity,
            $product->in_stock ? 'Yes' : 'No',
            $product->categories->pluck('name')->implode(', '),
            $product->tags->pluck('name')->implode(', '),
            $product->weight,
            $product->length,
            $product->width,
            $product->height,
            $product->is_active ? 'Active' : 'Inactive',
            $product->is_featured ? 'Yes' : 'No',
            $product->view_count,
            $product->created_at->format('Y-m-d H:i:s'),
            $product->updated_at->format('Y-m-d H:i:s')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
