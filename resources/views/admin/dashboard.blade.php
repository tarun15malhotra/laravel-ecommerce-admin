@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="p-4 sm:p-6 lg:p-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Dashboard Overview</h1>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Welcome back! Here's what's happening with your store today.</p>
    </div>
    
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Sales Card -->
        <div class="relative overflow-hidden bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl shadow-lg transform hover:scale-105 transition-transform duration-200">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-indigo-100 text-sm font-medium">Total Sales</p>
                        <p class="text-3xl font-bold text-white mt-2">${{ number_format($totalSales ?? 125420.50, 2) }}</p>
                        <div class="flex items-center mt-2">
                            <svg class="w-4 h-4 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                            <span class="text-green-300 text-sm ml-1">+12.5%</span>
                            <span class="text-indigo-100 text-sm ml-2">vs last month</span>
                        </div>
                    </div>
                    <div class="bg-white/20 rounded-lg p-3">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-white/20 to-transparent"></div>
        </div>
        
        <!-- Orders Card -->
        <div class="relative overflow-hidden bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl shadow-lg transform hover:scale-105 transition-transform duration-200">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-emerald-100 text-sm font-medium">Total Orders</p>
                        <p class="text-3xl font-bold text-white mt-2">{{ $totalOrders ?? 542 }}</p>
                        <div class="flex items-center mt-2">
                            <svg class="w-4 h-4 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                            <span class="text-green-300 text-sm ml-1">+8.2%</span>
                            <span class="text-emerald-100 text-sm ml-2">vs last month</span>
                        </div>
                    </div>
                    <div class="bg-white/20 rounded-lg p-3">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Customers Card -->
        <div class="relative overflow-hidden bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl shadow-lg transform hover:scale-105 transition-transform duration-200">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm font-medium">Total Customers</p>
                        <p class="text-3xl font-bold text-white mt-2">{{ $totalCustomers ?? 1254 }}</p>
                        <div class="flex items-center mt-2">
                            <svg class="w-4 h-4 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                            <span class="text-green-300 text-sm ml-1">+18.7%</span>
                            <span class="text-purple-100 text-sm ml-2">vs last month</span>
                        </div>
                    </div>
                    <div class="bg-white/20 rounded-lg p-3">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Revenue Card -->
        <div class="relative overflow-hidden bg-gradient-to-br from-orange-500 to-red-500 rounded-xl shadow-lg transform hover:scale-105 transition-transform duration-200">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-orange-100 text-sm font-medium">Avg Order Value</p>
                        <p class="text-3xl font-bold text-white mt-2">${{ number_format($avgOrderValue ?? 231.42, 2) }}</p>
                        <div class="flex items-center mt-2">
                            <svg class="w-4 h-4 text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                            </svg>
                            <span class="text-red-300 text-sm ml-1">-2.4%</span>
                            <span class="text-orange-100 text-sm ml-2">vs last month</span>
                        </div>
                    </div>
                    <div class="bg-white/20 rounded-lg p-3">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Sales Chart -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Sales Trends</h2>
                <select class="text-sm border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                    <option>Last 7 days</option>
                    <option>Last 30 days</option>
                    <option>Last 3 months</option>
                </select>
            </div>
            <div class="h-64 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                <p class="text-gray-500 dark:text-gray-400">Sales chart will be displayed here</p>
            </div>
        </div>
        
        <!-- Top Products -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Top Products</h2>
            <div class="space-y-4">
                @foreach([
                    ['name' => 'Premium Laptop Pro', 'sales' => 145, 'revenue' => 145000, 'color' => 'indigo'],
                    ['name' => 'Wireless Headphones', 'sales' => 98, 'revenue' => 9800, 'color' => 'emerald'],
                    ['name' => 'Smart Watch Ultra', 'sales' => 87, 'revenue' => 26100, 'color' => 'purple'],
                    ['name' => 'Gaming Mouse RGB', 'sales' => 76, 'revenue' => 4560, 'color' => 'orange'],
                    ['name' => 'Mechanical Keyboard', 'sales' => 65, 'revenue' => 8450, 'color' => 'pink']
                ] as $product)
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-{{ $product['color'] }}-100 dark:bg-{{ $product['color'] }}-900/20 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-{{ $product['color'] }}-600 dark:text-{{ $product['color'] }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $product['name'] }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $product['sales'] }} sales</p>
                        </div>
                    </div>
                    <p class="text-sm font-semibold text-gray-900 dark:text-white">${{ number_format($product['revenue'], 0) }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    
    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Orders -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Recent Orders</h2>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            <th class="text-left pb-3">Order ID</th>
                            <th class="text-left pb-3">Customer</th>
                            <th class="text-left pb-3">Status</th>
                            <th class="text-right pb-3">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach([
                            ['id' => '#1234', 'customer' => 'John Doe', 'status' => 'pending', 'total' => 125.50],
                            ['id' => '#1233', 'customer' => 'Jane Smith', 'status' => 'shipped', 'total' => 89.99],
                            ['id' => '#1232', 'customer' => 'Bob Johnson', 'status' => 'delivered', 'total' => 456.00],
                            ['id' => '#1231', 'customer' => 'Alice Brown', 'status' => 'processing', 'total' => 234.75],
                            ['id' => '#1230', 'customer' => 'Chris Davis', 'status' => 'cancelled', 'total' => 67.25]
                        ] as $order)
                        <tr class="text-sm">
                            <td class="py-3 font-medium text-gray-900 dark:text-white">{{ $order['id'] }}</td>
                            <td class="py-3 text-gray-600 dark:text-gray-300">{{ $order['customer'] }}</td>
                            <td class="py-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $order['status'] == 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400' : '' }}
                                    {{ $order['status'] == 'processing' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400' : '' }}
                                    {{ $order['status'] == 'shipped' ? 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/20 dark:text-indigo-400' : '' }}
                                    {{ $order['status'] == 'delivered' ? 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400' : '' }}
                                    {{ $order['status'] == 'cancelled' ? 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400' : '' }}">
                                    {{ ucfirst($order['status']) }}
                                </span>
                            </td>
                            <td class="py-3 text-right font-medium text-gray-900 dark:text-white">${{ number_format($order['total'], 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Activity Feed -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Activity Feed</h2>
            <div class="space-y-4">
                @foreach([
                    ['icon' => 'user', 'message' => 'New customer registered', 'time' => '2 min ago', 'color' => 'blue'],
                    ['icon' => 'cart', 'message' => 'New order #1235', 'time' => '15 min ago', 'color' => 'green'],
                    ['icon' => 'alert', 'message' => 'Low stock alert: Product XYZ', 'time' => '1 hour ago', 'color' => 'yellow'],
                    ['icon' => 'star', 'message' => 'New 5-star review', 'time' => '3 hours ago', 'color' => 'purple'],
                ] as $activity)
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-{{ $activity['color'] }}-100 dark:bg-{{ $activity['color'] }}-900/20 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-{{ $activity['color'] }}-600 dark:text-{{ $activity['color'] }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                @if($activity['icon'] == 'user')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                @elseif($activity['icon'] == 'cart')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                @elseif($activity['icon'] == 'alert')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                @else
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                @endif
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $activity['message'] }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $activity['time'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
