@extends('layouts.admin')

@section('title', 'Products')

@section('content')
<div class="p-4 sm:p-6 lg:p-8">
    <!-- Page Header -->
    <div class="sm:flex sm:items-center sm:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Products</h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Manage your product catalog and inventory</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('admin.products.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Product
            </a>
        </div>
    </div>
    
    <!-- Filters Bar -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search -->
            <div class="md:col-span-2">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="search" placeholder="Search products..." class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>
            </div>
            
            <!-- Category Filter -->
            <div>
                <select class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <option value="">All Categories</option>
                    <option value="electronics">Electronics</option>
                    <option value="clothing">Clothing</option>
                    <option value="books">Books</option>
                    <option value="home">Home & Garden</option>
                </select>
            </div>
            
            <!-- Stock Filter -->
            <div>
                <select class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <option value="">All Stock Levels</option>
                    <option value="in_stock">In Stock</option>
                    <option value="low_stock">Low Stock</option>
                    <option value="out_of_stock">Out of Stock</option>
                </select>
            </div>
        </div>
    </div>
    
    <!-- Products Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Product</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Stock</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($products ?? [] as $product)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-12 w-12">
                                    <img class="h-12 w-12 rounded-lg object-cover" src="{{ $product->image_url ?? 'https://via.placeholder.com/100' }}" alt="{{ $product->name }}">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $product->name }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">SKU: {{ $product->sku }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                            {{ $product->category->name ?? 'Uncategorized' }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">${{ number_format($product->price, 2) }}</div>
                            @if($product->sale_price)
                            <div class="text-xs text-gray-500 dark:text-gray-400 line-through">${{ number_format($product->sale_price, 2) }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $stock = $product->stock_quantity ?? rand(0, 100);
                                $stockStatus = $stock > 20 ? 'in_stock' : ($stock > 0 ? 'low_stock' : 'out_of_stock');
                            @endphp
                            <div class="flex items-center">
                                <span class="text-sm font-medium text-gray-900 dark:text-white mr-2">{{ $stock }}</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $stockStatus == 'in_stock' ? 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400' : '' }}
                                    {{ $stockStatus == 'low_stock' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400' : '' }}
                                    {{ $stockStatus == 'out_of_stock' ? 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400' : '' }}">
                                    {{ $stockStatus == 'in_stock' ? 'In Stock' : ($stockStatus == 'low_stock' ? 'Low Stock' : 'Out of Stock') }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ ($product->status ?? 'active') == 'active' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/20 dark:text-emerald-400' : 'bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-400' }}">
                                {{ ucfirst($product->status ?? 'active') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('admin.products.show', $product->id ?? 1) }}" class="text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                                <a href="{{ route('admin.products.edit', $product->id ?? 1) }}" class="text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                <button class="text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <!-- Sample Products -->
                    @foreach([
                        ['name' => 'Premium Laptop Pro', 'sku' => 'LAP-001', 'category' => 'Electronics', 'price' => 1299.99, 'sale_price' => 999.99, 'stock' => 45, 'status' => 'active'],
                        ['name' => 'Wireless Headphones', 'sku' => 'HEAD-002', 'category' => 'Electronics', 'price' => 99.99, 'sale_price' => null, 'stock' => 120, 'status' => 'active'],
                        ['name' => 'Smart Watch Ultra', 'sku' => 'WAT-003', 'category' => 'Electronics', 'price' => 299.99, 'sale_price' => 249.99, 'stock' => 8, 'status' => 'active'],
                        ['name' => 'Gaming Mouse RGB', 'sku' => 'MOU-004', 'category' => 'Accessories', 'price' => 59.99, 'sale_price' => null, 'stock' => 0, 'status' => 'inactive'],
                        ['name' => 'Mechanical Keyboard', 'sku' => 'KEY-005', 'category' => 'Accessories', 'price' => 129.99, 'sale_price' => 99.99, 'stock' => 35, 'status' => 'active'],
                    ] as $product)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-12 w-12">
                                    <img class="h-12 w-12 rounded-lg object-cover" src="https://via.placeholder.com/100" alt="{{ $product['name'] }}">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $product['name'] }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">SKU: {{ $product['sku'] }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                            {{ $product['category'] }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">${{ number_format($product['price'], 2) }}</div>
                            @if($product['sale_price'])
                            <div class="text-xs text-gray-500 dark:text-gray-400 line-through">${{ number_format($product['sale_price'], 2) }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $stockStatus = $product['stock'] > 20 ? 'in_stock' : ($product['stock'] > 0 ? 'low_stock' : 'out_of_stock');
                            @endphp
                            <div class="flex items-center">
                                <span class="text-sm font-medium text-gray-900 dark:text-white mr-2">{{ $product['stock'] }}</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $stockStatus == 'in_stock' ? 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400' : '' }}
                                    {{ $stockStatus == 'low_stock' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400' : '' }}
                                    {{ $stockStatus == 'out_of_stock' ? 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400' : '' }}">
                                    {{ $stockStatus == 'in_stock' ? 'In Stock' : ($stockStatus == 'low_stock' ? 'Low Stock' : 'Out of Stock') }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $product['status'] == 'active' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/20 dark:text-emerald-400' : 'bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-400' }}">
                                {{ ucfirst($product['status']) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('admin.products.show', 1) }}" class="text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                                <a href="{{ route('admin.products.edit', 1) }}" class="text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                <button class="text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="bg-white dark:bg-gray-800 px-4 py-3 border-t border-gray-200 dark:border-gray-700 sm:px-6">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700 dark:text-gray-300">
                    Showing <span class="font-medium">1</span> to <span class="font-medium">5</span> of <span class="font-medium">20</span> results
                </div>
                <div class="flex space-x-2">
                    <button class="px-3 py-1 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">Previous</button>
                    <button class="px-3 py-1 bg-indigo-600 rounded-lg text-sm font-medium text-white hover:bg-indigo-700 transition-colors">1</button>
                    <button class="px-3 py-1 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">2</button>
                    <button class="px-3 py-1 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">3</button>
                    <button class="px-3 py-1 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">Next</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
