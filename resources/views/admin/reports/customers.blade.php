@extends('layouts.admin')

@section('title', 'Customer Reports')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Customer Reports</h1>
            <div class="flex space-x-3">
                <button class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Export Report
                </button>
            </div>
        </div>

        <!-- Date Range Filter -->
        <div class="bg-white shadow rounded-lg mb-6 p-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Date From</label>
                    <input type="date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ $startDate->format('Y-m-d') ?? '' }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Date To</label>
                    <input type="date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ $endDate->format('Y-m-d') ?? '' }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Period</label>
                    <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option>Last 30 Days</option>
                        <option>Last 7 Days</option>
                        <option>This Month</option>
                        <option>Last Month</option>
                        <option>This Year</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button class="w-full inline-flex justify-center items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                        Apply Filter
                    </button>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-100 rounded-md p-3">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">New Customers</dt>
                            <dd class="text-2xl font-semibold text-gray-900">{{ $newCustomers ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Active Customers</dt>
                            <dd class="text-2xl font-semibold text-gray-900">{{ $activeCustomers ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-yellow-100 rounded-md p-3">
                        <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Returning</dt>
                            <dd class="text-2xl font-semibold text-gray-900">{{ $returningCustomers ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-purple-100 rounded-md p-3">
                        <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Retention Rate</dt>
                            <dd class="text-2xl font-semibold text-gray-900">{{ $retentionRate ?? 0 }}%</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Avg LTV</dt>
                            <dd class="text-2xl font-semibold text-gray-900">${{ number_format($avgLifetimeValue ?? 0, 2) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Customer Segments -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Customer Segments</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Segment</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Count</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Avg Spent</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Avg Orders</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($customerSegments ?? [] as $segment)
                            <tr>
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $segment->customer_group == 'vip' ? 'bg-yellow-100 text-yellow-800' : 
                                           ($segment->customer_group == 'wholesale' ? 'bg-purple-100 text-purple-800' : 
                                           'bg-gray-100 text-gray-800') }}">
                                        {{ ucfirst($segment->customer_group ?? 'regular') }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $segment->count ?? 0 }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900">${{ number_format($segment->avg_spent ?? 0, 2) }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ number_format($segment->avg_order_count ?? 0, 1) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-4 py-3 text-sm text-gray-500 text-center">No segment data available</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Top Customers by Revenue -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Top Customers by Revenue</h2>
                <div class="space-y-3">
                    @forelse($topCustomersByRevenue ?? [] as $customer)
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm font-medium text-gray-900">
                                {{ $customer->customer->first_name ?? 'Customer' }} {{ $customer->customer->last_name ?? '' }}
                            </div>
                            <div class="text-xs text-gray-500">{{ $customer->order_count ?? 0 }} orders</div>
                        </div>
                        <div class="text-sm font-semibold text-green-600">
                            ${{ number_format($customer->total_spent ?? 0, 2) }}
                        </div>
                    </div>
                    @empty
                    <p class="text-sm text-gray-500 text-center">No customer data available</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Customer Distribution by Group -->
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Customer Distribution</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @forelse($customersByCountry ?? [] as $group)
                <div class="text-center">
                    <div class="text-2xl font-bold text-gray-900">{{ $group->count ?? 0 }}</div>
                    <div class="text-sm text-gray-500">{{ ucfirst($group->customer_group ?? 'Unknown') }}</div>
                    <div class="mt-2 h-2 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-indigo-600" style="width: {{ ($group->count / max(1, $customersByCountry->sum('count'))) * 100 }}%"></div>
                    </div>
                </div>
                @empty
                <div class="col-span-3 text-center text-gray-500">
                    <p>No distribution data available</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
