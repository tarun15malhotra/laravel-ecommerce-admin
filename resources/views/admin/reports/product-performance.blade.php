@extends('layouts.admin')

@section('title', 'Product Performance Report')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Product Performance Report</h1>
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
                    <input type="date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Date To</label>
                    <input type="date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Category</label>
                    <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option>All Categories</option>
                        <option>Electronics</option>
                        <option>Clothing</option>
                        <option>Home & Garden</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button class="w-full inline-flex justify-center items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                        Apply Filter
                    </button>
                </div>
            </div>
        </div>

        <!-- Top Performing Products -->
        <div class="bg-white shadow rounded-lg mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Top Performing Products</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rank</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Units Sold</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Revenue</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Views</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Conversion Rate</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trend</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @for($i = 1; $i <= 10; $i++)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $i }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">Sample Product {{ $i }}</div>
                                <div class="text-sm text-gray-500">SKU-{{ str_pad($i, 4, '0', STR_PAD_LEFT) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ rand(50, 500) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">${{ number_format(rand(5000, 50000), 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ rand(500, 5000) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ rand(10, 30) }}%</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if(rand(0, 1))
                                    <span class="inline-flex items-center text-green-600">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                                        </svg>
                                        {{ rand(5, 25) }}%
                                    </span>
                                @else
                                    <span class="inline-flex items-center text-red-600">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                        </svg>
                                        {{ rand(5, 15) }}%
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Performance by Category -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Performance by Category</h2>
                <div class="space-y-4">
                    @foreach(['Electronics' => 45, 'Clothing' => 30, 'Home & Garden' => 15, 'Sports' => 10] as $category => $percentage)
                    <div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">{{ $category }}</span>
                            <span class="font-semibold text-gray-900">{{ $percentage }}%</span>
                        </div>
                        <div class="mt-1 w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Low Performing Products -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Low Performing Products</h2>
                <div class="text-sm text-gray-500 mb-3">Products requiring attention</div>
                <div class="space-y-3">
                    @for($i = 1; $i <= 5; $i++)
                    <div class="flex items-center justify-between p-3 bg-red-50 rounded">
                        <div>
                            <div class="text-sm font-medium text-gray-900">Product Name {{ $i }}</div>
                            <div class="text-xs text-gray-500">{{ rand(0, 5) }} sales last month</div>
                        </div>
                        <a href="#" class="text-sm text-indigo-600 hover:text-indigo-500">View Details</a>
                    </div>
                    @endfor
                </div>
            </div>

            <!-- Stock Performance -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Stock Performance</h2>
                <div class="space-y-3">
                    <div class="flex justify-between items-center p-3 bg-green-50 rounded">
                        <span class="text-sm text-gray-700">Fast Moving Items</span>
                        <span class="text-2xl font-bold text-green-600">28</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-yellow-50 rounded">
                        <span class="text-sm text-gray-700">Slow Moving Items</span>
                        <span class="text-2xl font-bold text-yellow-600">15</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-red-50 rounded">
                        <span class="text-sm text-gray-700">Dead Stock</span>
                        <span class="text-2xl font-bold text-red-600">7</span>
                    </div>
                </div>
            </div>

            <!-- Return Rate Analysis -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Return Rate Analysis</h2>
                <div class="space-y-3">
                    @foreach(['Electronics' => 5, 'Clothing' => 12, 'Home & Garden' => 3, 'Sports' => 8] as $category => $rate)
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">{{ $category }}</span>
                        <span class="text-sm font-semibold {{ $rate > 10 ? 'text-red-600' : ($rate > 5 ? 'text-yellow-600' : 'text-green-600') }}">
                            {{ $rate }}% returns
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
