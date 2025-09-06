<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SalesReportExport implements WithMultipleSheets
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function sheets(): array
    {
        return [
            new SalesSummarySheet($this->data),
            new TopProductsSheet($this->data['topProducts']),
            new TopCustomersSheet($this->data['topCustomers']),
        ];
    }
}

class SalesSummarySheet implements FromArray, WithTitle, WithHeadings, ShouldAutoSize
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        $rows = [];
        
        foreach ($this->data['salesData'] as $sale) {
            $rows[] = [
                $sale->date,
                $sale->order_count,
                number_format($sale->total_sales, 2),
                number_format($sale->average_order_value, 2)
            ];
        }

        return $rows;
    }

    public function headings(): array
    {
        return ['Date', 'Order Count', 'Total Sales', 'Average Order Value'];
    }

    public function title(): string
    {
        return 'Sales Summary';
    }
}

class TopProductsSheet implements FromArray, WithTitle, WithHeadings, ShouldAutoSize
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
                $product->name,
                $product->sku,
                $product->total_quantity,
                number_format($product->total_revenue, 2)
            ];
        }

        return $rows;
    }

    public function headings(): array
    {
        return ['Product Name', 'SKU', 'Quantity Sold', 'Total Revenue'];
    }

    public function title(): string
    {
        return 'Top Products';
    }
}

class TopCustomersSheet implements FromArray, WithTitle, WithHeadings, ShouldAutoSize
{
    protected $customers;

    public function __construct($customers)
    {
        $this->customers = $customers;
    }

    public function array(): array
    {
        $rows = [];
        
        foreach ($this->customers as $order) {
            $rows[] = [
                $order->customer->full_name ?? 'Guest',
                $order->customer->email ?? 'N/A',
                $order->order_count,
                number_format($order->total_spent, 2)
            ];
        }

        return $rows;
    }

    public function headings(): array
    {
        return ['Customer Name', 'Email', 'Order Count', 'Total Spent'];
    }

    public function title(): string
    {
        return 'Top Customers';
    }
}
