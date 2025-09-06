@extends('layouts.admin')

@section('title', 'Customer Analytics')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Customer Analytics</h1>
            <div class="flex space-x-3">
                <button class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Export Report
                </button>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-100 rounded-md p-3">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Customers</dt>
                            <dd class="text-2xl font-semibold text-gray-900">{{ $totalCustomers ?? 75 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">New This Month</dt>
                            <dd class="text-2xl font-semibold text-gray-900">{{ $newThisMonth ?? 12 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-yellow-100 rounded-md p-3">
                        <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Average LTV</dt>
                            <dd class="text-2xl font-semibold text-gray-900">${{ number_format($avgLTV ?? 385.50, 2) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-purple-100 rounded-md p-3">
                        <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Retention Rate</dt>
                            <dd class="text-2xl font-semibold text-gray-900">{{ $retentionRate ?? 68 }}%</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Top Customers -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Top Customers</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Orders</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Total Spent</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @for($i = 1; $i <= 5; $i++)
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="text-sm font-medium text-gray-900">Customer {{ $i }}</div>
                                    <div class="text-sm text-gray-500">customer{{ $i }}@example.com</div>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ rand(10, 50) }}</td>
                                <td class="px-4 py-3 text-sm font-semibold text-gray-900">${{ number_format(rand(1000, 10000), 2) }}</td>
                            </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Customer Segments -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Customer Segments</h2>
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-sm text-gray-600">New Customers</span>
                            <span class="text-sm font-semibold text-gray-900">15%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: 15%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-sm text-gray-600">Regular Customers</span>
                            <span class="text-sm font-semibold text-gray-900">45%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-600 h-2 rounded-full" style="width: 45%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-sm text-gray-600">VIP Customers</span>
                            <span class="text-sm font-semibold text-gray-900">25%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-yellow-600 h-2 rounded-full" style="width: 25%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-sm text-gray-600">Inactive</span>
                            <span class="text-sm font-semibold text-gray-900">15%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-red-600 h-2 rounded-full" style="width: 15%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Activity Timeline -->
        <div class="bg-white shadow rounded-lg p-6 mb-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Customer Registration Trend</h2>
            <div class="h-64 bg-gray-100 rounded flex items-center justify-center">
                <p class="text-gray-500">Customer registration chart will be displayed here</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Geographic Distribution -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Geographic Distribution</h2>
                <div class="space-y-3">
                    @foreach(['United States' => 45, 'United Kingdom' => 20, 'Canada' => 15, 'Australia' => 12, 'Others' => 8] as $country => $percentage)
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">{{ $country }}</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $percentage }}%</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Purchase Frequency -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Purchase Frequency</h2>
                <div class="space-y-3">
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                        <span class="text-sm text-gray-700">Weekly Buyers</span>
                        <span class="text-lg font-bold text-gray-900">8</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                        <span class="text-sm text-gray-700">Monthly Buyers</span>
                        <span class="text-lg font-bold text-gray-900">32</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                        <span class="text-sm text-gray-700">Occasional Buyers</span>
                        <span class="text-lg font-bold text-gray-900">35</span>
                    </div>
                </div>
            </div>

            <!-- Engagement Metrics -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Engagement Metrics</h2>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Newsletter Subscribers</span>
                        <span class="text-sm font-semibold text-green-600">68%</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Email Open Rate</span>
                        <span class="text-sm font-semibold text-blue-600">24%</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Click-through Rate</span>
                        <span class="text-sm font-semibold text-purple-600">12%</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Review Rate</span>
                        <span class="text-sm font-semibold text-yellow-600">35%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
