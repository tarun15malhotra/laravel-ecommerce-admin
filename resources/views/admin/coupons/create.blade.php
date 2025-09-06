@extends('layouts.admin')

@section('title', 'Create Coupon')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center">
                <a href="{{ route('admin.coupons.index') }}" class="mr-4">
                    <svg class="h-6 w-6 text-gray-600 hover:text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <h1 class="text-2xl font-semibold text-gray-900">Create New Coupon</h1>
            </div>
        </div>

        <form action="{{ route('admin.coupons.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Basic Information -->
                    <div class="bg-white shadow rounded-lg p-6">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h2>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="code" class="block text-sm font-medium text-gray-700">Coupon Code *</label>
                                <input type="text" name="code" id="code" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm uppercase" value="{{ old('code') }}" placeholder="e.g., SAVE20">
                                @error('code')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700">Description *</label>
                                <input type="text" name="description" id="description" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('description') }}" placeholder="e.g., 20% off all products">
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="discount_type" class="block text-sm font-medium text-gray-700">Discount Type *</label>
                                    <select name="discount_type" id="discount_type" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        <option value="fixed" {{ old('discount_type') == 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                                        <option value="percentage" {{ old('discount_type') == 'percentage' ? 'selected' : '' }}>Percentage</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="discount_value" class="block text-sm font-medium text-gray-700">Discount Value *</label>
                                    <input type="number" name="discount_value" id="discount_value" required step="0.01" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('discount_value') }}">
                                    @error('discount_value')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Usage Restrictions -->
                    <div class="bg-white shadow rounded-lg p-6">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">Usage Restrictions</h2>
                        
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="minimum_amount" class="block text-sm font-medium text-gray-700">Minimum Purchase Amount</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">$</span>
                                        </div>
                                        <input type="number" name="minimum_amount" id="minimum_amount" step="0.01" class="pl-7 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('minimum_amount') }}" placeholder="0.00">
                                    </div>
                                </div>

                                <div>
                                    <label for="maximum_discount" class="block text-sm font-medium text-gray-700">Maximum Discount</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">$</span>
                                        </div>
                                        <input type="number" name="maximum_discount" id="maximum_discount" step="0.01" class="pl-7 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('maximum_discount') }}" placeholder="No limit">
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="usage_limit" class="block text-sm font-medium text-gray-700">Total Usage Limit</label>
                                    <input type="number" name="usage_limit" id="usage_limit" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('usage_limit') }}" placeholder="Unlimited">
                                </div>

                                <div>
                                    <label for="usage_limit_per_customer" class="block text-sm font-medium text-gray-700">Usage Limit Per Customer</label>
                                    <input type="number" name="usage_limit_per_customer" id="usage_limit_per_customer" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('usage_limit_per_customer', 1) }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Validity Period -->
                    <div class="bg-white shadow rounded-lg p-6">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">Validity Period</h2>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="valid_from" class="block text-sm font-medium text-gray-700">Valid From *</label>
                                <input type="datetime-local" name="valid_from" id="valid_from" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('valid_from', now()->format('Y-m-d\TH:i')) }}">
                                @error('valid_from')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="valid_until" class="block text-sm font-medium text-gray-700">Valid Until *</label>
                                <input type="datetime-local" name="valid_until" id="valid_until" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('valid_until', now()->addMonth()->format('Y-m-d\TH:i')) }}">
                                @error('valid_until')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Status -->
                    <div class="bg-white shadow rounded-lg p-6">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">Status</h2>
                        
                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" checked>
                            <span class="ml-2 text-sm text-gray-700">Active</span>
                        </label>
                    </div>

                    <!-- Coupon Type -->
                    <div class="bg-white shadow rounded-lg p-6">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">Coupon Type</h2>
                        
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="radio" name="type" value="public" class="text-indigo-600 focus:ring-indigo-500" {{ old('type', 'public') == 'public' ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700">Public (Anyone can use)</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="type" value="private" class="text-indigo-600 focus:ring-indigo-500" {{ old('type') == 'private' ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700">Private (Specific customers)</span>
                            </label>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="bg-white shadow rounded-lg p-6">
                        <div class="flex flex-col space-y-3">
                            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                                Create Coupon
                            </button>
                            <a href="{{ route('admin.coupons.index') }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 transition">
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
