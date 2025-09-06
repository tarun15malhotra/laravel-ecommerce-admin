@extends('layouts.admin')

@section('title', 'Edit Customer')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center">
                <a href="{{ route('admin.customers.index') }}" class="mr-4">
                    <svg class="h-6 w-6 text-gray-600 hover:text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <h1 class="text-2xl font-semibold text-gray-900">Edit Customer</h1>
            </div>
        </div>

        <form action="{{ route('admin.customers.update', $customer ?? 1) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Personal Information -->
                    <div class="bg-white shadow rounded-lg p-6">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">Personal Information</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="first_name" class="block text-sm font-medium text-gray-700">First Name *</label>
                                <input type="text" name="first_name" id="first_name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('first_name', $customer->first_name ?? 'John') }}">
                                @error('first_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name *</label>
                                <input type="text" name="last_name" id="last_name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('last_name', $customer->last_name ?? 'Doe') }}">
                                @error('last_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email *</label>
                                <input type="email" name="email" id="email" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('email', $customer->email ?? 'john.doe@example.com') }}">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                                <input type="tel" name="phone" id="phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('phone', $customer->phone ?? '') }}">
                            </div>

                            <div>
                                <label for="date_of_birth" class="block text-sm font-medium text-gray-700">Date of Birth</label>
                                <input type="date" name="date_of_birth" id="date_of_birth" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('date_of_birth', $customer->date_of_birth ?? '') }}">
                            </div>

                            <div>
                                <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                                <select name="gender" id="gender" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">Select Gender</option>
                                    <option value="male" {{ old('gender', $customer->gender ?? '') == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender', $customer->gender ?? '') == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('gender', $customer->gender ?? '') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Account Settings -->
                    <div class="bg-white shadow rounded-lg p-6">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">Account Settings</h2>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
                                <input type="password" name="password" id="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <p class="mt-1 text-xs text-gray-500">Leave empty to keep current password</p>
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                        </div>
                    </div>

                    <!-- Preferences -->
                    <div class="bg-white shadow rounded-lg p-6">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">Preferences</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="preferred_language" class="block text-sm font-medium text-gray-700">Preferred Language</label>
                                <select name="preferred_language" id="preferred_language" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="en" {{ old('preferred_language', $customer->preferred_language ?? 'en') == 'en' ? 'selected' : '' }}>English</option>
                                    <option value="es" {{ old('preferred_language', $customer->preferred_language ?? '') == 'es' ? 'selected' : '' }}>Spanish</option>
                                    <option value="fr" {{ old('preferred_language', $customer->preferred_language ?? '') == 'fr' ? 'selected' : '' }}>French</option>
                                </select>
                            </div>

                            <div>
                                <label for="preferred_currency" class="block text-sm font-medium text-gray-700">Preferred Currency</label>
                                <select name="preferred_currency" id="preferred_currency" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="USD" {{ old('preferred_currency', $customer->preferred_currency ?? 'USD') == 'USD' ? 'selected' : '' }}>USD</option>
                                    <option value="EUR" {{ old('preferred_currency', $customer->preferred_currency ?? '') == 'EUR' ? 'selected' : '' }}>EUR</option>
                                    <option value="GBP" {{ old('preferred_currency', $customer->preferred_currency ?? '') == 'GBP' ? 'selected' : '' }}>GBP</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-4 space-y-3">
                            <label class="flex items-center">
                                <input type="checkbox" name="accepts_newsletter" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" {{ old('accepts_newsletter', $customer->accepts_newsletter ?? false) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700">Subscribe to newsletter</span>
                            </label>

                            <label class="flex items-center">
                                <input type="checkbox" name="accepts_marketing" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" {{ old('accepts_marketing', $customer->accepts_marketing ?? false) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700">Accept marketing emails</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Status -->
                    <div class="bg-white shadow rounded-lg p-6">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">Account Status</h2>
                        
                        <div class="space-y-3">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" {{ old('is_active', $customer->is_active ?? true) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700">Active Account</span>
                            </label>

                            <label class="flex items-center">
                                <input type="checkbox" name="email_verified" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" {{ old('email_verified', $customer->email_verified ?? true) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700">Email Verified</span>
                            </label>
                        </div>
                    </div>

                    <!-- Customer Group -->
                    <div class="bg-white shadow rounded-lg p-6">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">Customer Group</h2>
                        
                        <div>
                            <label for="customer_group" class="block text-sm font-medium text-gray-700">Group</label>
                            <select name="customer_group" id="customer_group" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="regular" {{ old('customer_group', $customer->customer_group ?? 'regular') == 'regular' ? 'selected' : '' }}>Regular</option>
                                <option value="vip" {{ old('customer_group', $customer->customer_group ?? '') == 'vip' ? 'selected' : '' }}>VIP</option>
                                <option value="wholesale" {{ old('customer_group', $customer->customer_group ?? '') == 'wholesale' ? 'selected' : '' }}>Wholesale</option>
                            </select>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="bg-white shadow rounded-lg p-6">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">Internal Notes</h2>
                        <textarea name="notes" rows="4" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Add notes about this customer...">{{ old('notes', $customer->notes ?? '') }}</textarea>
                    </div>

                    <!-- Actions -->
                    <div class="bg-white shadow rounded-lg p-6">
                        <div class="flex flex-col space-y-3">
                            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                                Update Customer
                            </button>
                            <a href="{{ route('admin.customers.show', $customer ?? 1) }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 transition">
                                View Customer
                            </a>
                            <a href="{{ route('admin.customers.index') }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200 transition">
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
