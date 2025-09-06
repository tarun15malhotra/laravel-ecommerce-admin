@extends('layouts.admin')

@section('title', 'Settings')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Store Settings</h1>
            <p class="mt-1 text-sm text-gray-600">Manage your store configuration and preferences</p>
        </div>

        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <div class="space-y-6">
                <!-- General Settings -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">General Settings</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="store_name" class="block text-sm font-medium text-gray-700">Store Name</label>
                            <input type="text" name="store_name" id="store_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('store_name', $settings['store_name'] ?? 'My Store') }}">
                        </div>
                        <div>
                            <label for="store_email" class="block text-sm font-medium text-gray-700">Store Email</label>
                            <input type="email" name="store_email" id="store_email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('store_email', $settings['store_email'] ?? '') }}">
                        </div>
                        <div>
                            <label for="store_phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                            <input type="text" name="store_phone" id="store_phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('store_phone', $settings['store_phone'] ?? '') }}">
                        </div>
                        <div>
                            <label for="store_address" class="block text-sm font-medium text-gray-700">Address</label>
                            <input type="text" name="store_address" id="store_address" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('store_address', $settings['store_address'] ?? '') }}">
                        </div>
                        <div class="md:col-span-2">
                            <label for="store_description" class="block text-sm font-medium text-gray-700">Store Description</label>
                            <textarea name="store_description" id="store_description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('store_description', $settings['store_description'] ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Currency & Tax Settings -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Currency & Tax</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="currency" class="block text-sm font-medium text-gray-700">Currency</label>
                            <select name="currency" id="currency" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="USD" {{ ($settings['currency'] ?? 'USD') == 'USD' ? 'selected' : '' }}>USD ($)</option>
                                <option value="EUR" {{ ($settings['currency'] ?? '') == 'EUR' ? 'selected' : '' }}>EUR (€)</option>
                                <option value="GBP" {{ ($settings['currency'] ?? '') == 'GBP' ? 'selected' : '' }}>GBP (£)</option>
                            </select>
                        </div>
                        <div>
                            <label for="tax_enabled" class="block text-sm font-medium text-gray-700">Enable Tax</label>
                            <select name="tax_enabled" id="tax_enabled" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="1" {{ ($settings['tax_enabled'] ?? false) ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ !($settings['tax_enabled'] ?? false) ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                        <div>
                            <label for="tax_rate" class="block text-sm font-medium text-gray-700">Tax Rate (%)</label>
                            <input type="number" name="tax_rate" id="tax_rate" step="0.01" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('tax_rate', $settings['tax_rate'] ?? '0') }}">
                        </div>
                    </div>
                </div>

                <!-- Shipping Settings -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Shipping</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="shipping_enabled" class="block text-sm font-medium text-gray-700">Enable Shipping</label>
                            <select name="shipping_enabled" id="shipping_enabled" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="1" {{ ($settings['shipping_enabled'] ?? true) ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ !($settings['shipping_enabled'] ?? true) ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                        <div>
                            <label for="free_shipping_threshold" class="block text-sm font-medium text-gray-700">Free Shipping Threshold ($)</label>
                            <input type="number" name="free_shipping_threshold" id="free_shipping_threshold" step="0.01" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('free_shipping_threshold', $settings['free_shipping_threshold'] ?? '50') }}">
                        </div>
                        <div>
                            <label for="flat_shipping_rate" class="block text-sm font-medium text-gray-700">Flat Shipping Rate ($)</label>
                            <input type="number" name="flat_shipping_rate" id="flat_shipping_rate" step="0.01" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('flat_shipping_rate', $settings['flat_shipping_rate'] ?? '5') }}">
                        </div>
                    </div>
                </div>

                <!-- Social Media -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Social Media</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="facebook_url" class="block text-sm font-medium text-gray-700">Facebook URL</label>
                            <input type="url" name="facebook_url" id="facebook_url" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('facebook_url', $settings['facebook_url'] ?? '') }}">
                        </div>
                        <div>
                            <label for="twitter_url" class="block text-sm font-medium text-gray-700">Twitter URL</label>
                            <input type="url" name="twitter_url" id="twitter_url" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('twitter_url', $settings['twitter_url'] ?? '') }}">
                        </div>
                        <div>
                            <label for="instagram_url" class="block text-sm font-medium text-gray-700">Instagram URL</label>
                            <input type="url" name="instagram_url" id="instagram_url" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('instagram_url', $settings['instagram_url'] ?? '') }}">
                        </div>
                        <div>
                            <label for="linkedin_url" class="block text-sm font-medium text-gray-700">LinkedIn URL</label>
                            <input type="url" name="linkedin_url" id="linkedin_url" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('linkedin_url', $settings['linkedin_url'] ?? '') }}">
                        </div>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="flex justify-end">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Save Settings
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
