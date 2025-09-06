@extends('layouts.admin')

@section('title', 'Create Product')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center">
                <a href="{{ route('admin.products.index') }}" class="mr-4">
                    <svg class="h-6 w-6 text-gray-600 hover:text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <h1 class="text-2xl font-semibold text-gray-900">Create New Product</h1>
            </div>
        </div>

        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Basic Information -->
                    <div class="bg-white shadow rounded-lg p-6">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h2>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Product Name *</label>
                                <input type="text" name="name" id="name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('name') }}">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="slug" class="block text-sm font-medium text-gray-700">Slug</label>
                                <input type="text" name="slug" id="slug" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('slug') }}">
                                <p class="mt-1 text-xs text-gray-500">Leave empty to auto-generate from product name</p>
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea name="description" id="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('description') }}</textarea>
                            </div>

                            <div>
                                <label for="short_description" class="block text-sm font-medium text-gray-700">Short Description</label>
                                <textarea name="short_description" id="short_description" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('short_description') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Pricing -->
                    <div class="bg-white shadow rounded-lg p-6">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">Pricing</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="price" class="block text-sm font-medium text-gray-700">Regular Price *</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">$</span>
                                    </div>
                                    <input type="number" name="price" id="price" step="0.01" required class="pl-7 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('price') }}">
                                </div>
                            </div>

                            <div>
                                <label for="sale_price" class="block text-sm font-medium text-gray-700">Sale Price</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">$</span>
                                    </div>
                                    <input type="number" name="sale_price" id="sale_price" step="0.01" class="pl-7 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('sale_price') }}">
                                </div>
                            </div>

                            <div>
                                <label for="cost_price" class="block text-sm font-medium text-gray-700">Cost Price</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">$</span>
                                    </div>
                                    <input type="number" name="cost_price" id="cost_price" step="0.01" class="pl-7 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('cost_price') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Inventory -->
                    <div class="bg-white shadow rounded-lg p-6">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">Inventory</h2>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="sku" class="block text-sm font-medium text-gray-700">SKU *</label>
                                <input type="text" name="sku" id="sku" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('sku') }}">
                            </div>

                            <div>
                                <label class="flex items-center">
                                    <input type="checkbox" name="track_quantity" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" {{ old('track_quantity') ? 'checked' : '' }}>
                                    <span class="ml-2 text-sm text-gray-700">Track quantity</span>
                                </label>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="stock_quantity" class="block text-sm font-medium text-gray-700">Stock Quantity</label>
                                    <input type="number" name="stock_quantity" id="stock_quantity" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('stock_quantity', 0) }}">
                                </div>

                                <div>
                                    <label for="low_stock_threshold" class="block text-sm font-medium text-gray-700">Low Stock Threshold</label>
                                    <input type="number" name="low_stock_threshold" id="low_stock_threshold" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('low_stock_threshold', 5) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Status -->
                    <div class="bg-white shadow rounded-lg p-6">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">Status</h2>
                        
                        <div class="space-y-3">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" checked>
                                <span class="ml-2 text-sm text-gray-700">Active</span>
                            </label>

                            <label class="flex items-center">
                                <input type="checkbox" name="is_featured" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-700">Featured</span>
                            </label>

                            <label class="flex items-center">
                                <input type="checkbox" name="in_stock" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" checked>
                                <span class="ml-2 text-sm text-gray-700">In Stock</span>
                            </label>
                        </div>
                    </div>

                    <!-- Images -->
                    <div class="bg-white shadow rounded-lg p-6">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">Product Images</h2>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Upload Images</label>
                            <input type="file" name="images[]" multiple accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            <p class="mt-1 text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="bg-white shadow rounded-lg p-6">
                        <div class="flex flex-col space-y-3">
                            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                                Create Product
                            </button>
                            <a href="{{ route('admin.products.index') }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 transition">
                                Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
