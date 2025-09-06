<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CustomersReportExport implements WithMultipleSheets
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function sheets(): array
    {
        return [
            new CustomerSegmentsSheet($this->data['customerSegments']),
            new TopCustomersByRevenueSheet($this->data['topCustomersByRevenue']),
            new GeographicDistributionSheet($this->data['customersByCountry']),
        ];
    }
}

class CustomerSegmentsSheet implements FromArray, WithTitle, WithHeadings, ShouldAutoSize
{
    protected $segments;

    public function __construct($segments)
    {
        $this->segments = $segments;
    }

    public function array(): array
    {
        $rows = [];
        
        foreach ($this->segments as $segment) {
            $rows[] = [
                $segment->customer_group ?? 'Default',
                $segment->count,
                number_format($segment->avg_spent, 2),
                number_format($segment->total_spent, 2)
            ];
        }

        return $rows;
    }

    public function headings(): array
    {
        return ['Customer Group', 'Customer Count', 'Average Spent', 'Total Spent'];
    }

    public function title(): string
    {
        return 'Customer Segments';
    }
}

class TopCustomersByRevenueSheet implements FromArray, WithTitle, WithHeadings, ShouldAutoSize
{
    protected $customers;

    public function __construct($customers)
    {
        $this->customers = $customers;
    }

    public function array(): array
    {
        $rows = [];
        
        foreach ($this->customers as $customer) {
            $rows[] = [
                $customer->full_name,
                $customer->email,
                $customer->phone,
                $customer->total_orders,
                number_format($customer->total_spent, 2),
                number_format($customer->average_order_value, 2),
                $customer->last_order_at ? $customer->last_order_at->format('Y-m-d') : 'N/A',
                $customer->created_at->format('Y-m-d')
            ];
        }

        return $rows;
    }

    public function headings(): array
    {
        return ['Name', 'Email', 'Phone', 'Total Orders', 'Total Spent', 'Avg Order Value', 'Last Order', 'Member Since'];
    }

    public function title(): string
    {
        return 'Top Customers by Revenue';
    }
}

class GeographicDistributionSheet implements FromArray, WithTitle, WithHeadings, ShouldAutoSize
{
    protected $countries;

    public function __construct($countries)
    {
        $this->countries = $countries;
    }

    public function array(): array
    {
        $rows = [];
        
        foreach ($this->countries as $country) {
            $rows[] = [
                $country->country,
                $country->count,
                round(($country->count / $this->countries->sum('count')) * 100, 2) . '%'
            ];
        }

        return $rows;
    }

    public function headings(): array
    {
        return ['Country', 'Customer Count', 'Percentage'];
    }

    public function title(): string
    {
        return 'Geographic Distribution';
    }
}
