<?php

namespace App\Exports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CustomersExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = Customer::query();

        // Apply filters
        if (!empty($this->filters['search'])) {
            $search = $this->filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if (!empty($this->filters['customer_group'])) {
            $query->where('customer_group', $this->filters['customer_group']);
        }

        if (isset($this->filters['is_active'])) {
            $query->where('is_active', $this->filters['is_active']);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'ID',
            'First Name',
            'Last Name',
            'Email',
            'Phone',
            'Date of Birth',
            'Gender',
            'Customer Group',
            'Total Orders',
            'Total Spent',
            'Average Order Value',
            'Last Order Date',
            'Status',
            'Email Verified',
            'Newsletter',
            'Country',
            'City',
            'Created At'
        ];
    }

    public function map($customer): array
    {
        return [
            $customer->id,
            $customer->first_name,
            $customer->last_name,
            $customer->email,
            $customer->phone,
            $customer->date_of_birth ? $customer->date_of_birth->format('Y-m-d') : '',
            $customer->gender,
            $customer->customer_group,
            $customer->total_orders,
            number_format($customer->total_spent, 2),
            number_format($customer->average_order_value, 2),
            $customer->last_order_at ? $customer->last_order_at->format('Y-m-d H:i:s') : '',
            $customer->is_active ? 'Active' : 'Inactive',
            $customer->email_verified_at ? 'Verified' : 'Not Verified',
            $customer->preferences['newsletter'] ?? false ? 'Subscribed' : 'Not Subscribed',
            $customer->country,
            $customer->city,
            $customer->created_at->format('Y-m-d H:i:s')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
