@extends('layouts.admin')

@section('title', 'Top Customers')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Top Customers</h1>
            <div class="flex space-x-3">
                <a href="{{ route('admin.customers.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 transition">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to All Customers
                </a>
                <button class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Export
                </button>
            </div>
        </div>

        <!-- Top Customers Table -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Top 20 Customers by Total Spending</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Customers ranked by their lifetime value</p>
            </div>
            <div class="border-t border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rank</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Orders</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Spent</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Average Order</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Group</th>
                            <th scope="col" class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($customers as $index => $customer)
                        <tr class="{{ $index < 3 ? 'bg-yellow-50' : '' }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($index < 3)
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-yellow-400 flex items-center justify-center">
                                                <span class="text-white font-bold">{{ $index + 1 }}</span>
                                            </div>
                                        </div>
                                    @else
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                <span class="text-gray-600 font-semibold">{{ $index + 1 }}</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $customer->first_name }} {{ $customer->last_name }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        Customer since {{ $customer->created_at->format('M Y') }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $customer->email }}</div>
                                @if($customer->phone)
                                    <div class="text-sm text-gray-500">{{ $customer->phone }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 font-semibold">{{ $customer->order_count ?? 0 }}</div>
                                <div class="text-xs text-gray-500">orders placed</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-green-600">
                                    ${{ number_format($customer->total_spent ?? 0, 2) }}
                                </div>
                                <div class="text-xs text-gray-500">lifetime value</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                ${{ $customer->order_count > 0 ? number_format(($customer->total_spent ?? 0) / $customer->order_count, 2) : '0.00' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $customer->customer_group == 'vip' ? 'bg-yellow-100 text-yellow-800' : 
                                       ($customer->customer_group == 'wholesale' ? 'bg-purple-100 text-purple-800' : 
                                       'bg-gray-100 text-gray-800') }}">
                                    {{ ucfirst($customer->customer_group ?? 'regular') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.customers.show', $customer) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">View</a>
                                <a href="{{ route('admin.customers.edit', $customer) }}" class="text-green-600 hover:text-green-900">Edit</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                    <p class="mt-2">No customers found</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Summary Stats -->
        @if($customers->count() > 0)
        <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-lg shadow p-4">
                <h4 class="text-sm font-medium text-gray-500">Total Revenue (Top 20)</h4>
                <p class="mt-1 text-2xl font-semibold text-gray-900">${{ number_format($customers->sum('total_spent'), 2) }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <h4 class="text-sm font-medium text-gray-500">Average Customer Value</h4>
                <p class="mt-1 text-2xl font-semibold text-gray-900">${{ number_format($customers->avg('total_spent'), 2) }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <h4 class="text-sm font-medium text-gray-500">Total Orders (Top 20)</h4>
                <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $customers->sum('order_count') }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <h4 class="text-sm font-medium text-gray-500">Average Orders per Customer</h4>
                <p class="mt-1 text-2xl font-semibold text-gray-900">{{ round($customers->avg('order_count'), 1) }}</p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
