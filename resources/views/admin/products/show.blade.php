@extends('layouts.admin')

@section('title', 'View Product')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <a href="{{ route('admin.products.index') }}" class="mr-4">
                        <svg class="h-6 w-6 text-gray-600 hover:text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </a>
                    <h1 class="text-2xl font-semibold text-gray-900">Product Details</h1>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.products.edit', $product ?? 1) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Product
                    </a>
                    <form action="{{ route('admin.products.destroy', $product ?? 1) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700" onclick="return confirm('Are you sure you want to delete this product?')">
                            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h2>
                    <dl class="grid grid-cols-1 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Product Name</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $product->name ?? 'Sample Product Name' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">SKU</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $product->sku ?? 'SKU-123456' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Slug</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $product->slug ?? 'sample-product' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Description</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $product->description ?? 'This is a sample product description that provides details about the product features and benefits.' }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Pricing -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Pricing Information</h2>
                    <dl class="grid grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Regular Price</dt>
                            <dd class="mt-1 text-2xl font-semibold text-gray-900">${{ number_format($product->price ?? 99.99, 2) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Sale Price</dt>
                            <dd class="mt-1 text-2xl font-semibold text-green-600">
                                @if($product->sale_price ?? false)
                                    ${{ number_format($product->sale_price, 2) }}
                                @else
                                    <span class="text-gray-400">Not on sale</span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Cost Price</dt>
                            <dd class="mt-1 text-sm text-gray-900">${{ number_format($product->cost_price ?? 49.99, 2) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Profit Margin</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @php
                                    $cost = $product->cost_price ?? 49.99;
                                    $price = $product->sale_price ?? $product->price ?? 99.99;
                                    $margin = (($price - $cost) / $price) * 100;
                                @endphp
                                {{ number_format($margin, 1) }}%
                            </dd>
                        </div>
                    </dl>
                </div>

                <!-- Inventory -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Inventory Details</h2>
                    <dl class="grid grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Stock Quantity</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($product->track_quantity ?? true)
                                    <span class="text-2xl font-semibold {{ ($product->stock_quantity ?? 50) <= ($product->low_stock_threshold ?? 10) ? 'text-red-600' : 'text-gray-900' }}">
                                        {{ $product->stock_quantity ?? 50 }}
                                    </span>
                                    @if(($product->stock_quantity ?? 50) <= ($product->low_stock_threshold ?? 10))
                                        <span class="ml-2 text-xs text-red-600 font-semibold">LOW STOCK</span>
                                    @endif
                                @else
                                    <span class="text-gray-500">Not tracked</span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Low Stock Threshold</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $product->low_stock_threshold ?? 10 }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Track Quantity</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ ($product->track_quantity ?? true) ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ ($product->track_quantity ?? true) ? 'Yes' : 'No' }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Allow Backorder</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ ($product->allow_backorder ?? false) ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ ($product->allow_backorder ?? false) ? 'Yes' : 'No' }}
                                </span>
                            </dd>
                        </div>
                    </dl>
                </div>

                <!-- Sales Statistics -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Sales Statistics</h2>
                    <dl class="grid grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Total Sales</dt>
                            <dd class="mt-1 text-2xl font-semibold text-gray-900">{{ $product->sales_count ?? 125 }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Revenue Generated</dt>
                            <dd class="mt-1 text-2xl font-semibold text-green-600">${{ number_format(($product->sales_count ?? 125) * ($product->price ?? 99.99), 2) }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Status -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Status</h2>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">Active Status</span>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ ($product->is_active ?? true) ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ ($product->is_active ?? true) ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">Featured</span>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ ($product->is_featured ?? false) ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ ($product->is_featured ?? false) ? 'Yes' : 'No' }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">In Stock</span>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ ($product->in_stock ?? true) ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ ($product->in_stock ?? true) ? 'Yes' : 'No' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Images -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Product Images</h2>
                    <div class="grid grid-cols-2 gap-2">
                        @forelse($product->images ?? [] as $image)
                            <img src="{{ Storage::url($image) }}" alt="Product image" class="w-full h-32 object-cover rounded-lg">
                        @empty
                            <div class="col-span-2 h-32 bg-gray-100 rounded-lg flex items-center justify-center">
                                <span class="text-gray-400">No images</span>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Metadata -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Metadata</h2>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Created</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ ($product->created_at ?? now())->format('M d, Y h:i A') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ ($product->updated_at ?? now())->format('M d, Y h:i A') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Weight</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $product->weight ?? '1.5' }} kg</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Dimensions</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $product->dimensions ?? '10 x 15 x 5 cm' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
