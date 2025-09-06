@extends('layouts.admin')

@section('title', 'Customer Details')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <a href="{{ route('admin.customers.index') }}" class="mr-4">
                        <svg class="h-6 w-6 text-gray-600 hover:text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </a>
                    <h1 class="text-2xl font-semibold text-gray-900">Customer Profile</h1>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.customers.edit', $customer ?? 1) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Customer
                    </a>
                    <button class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Send Email
                    </button>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h2>
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">First Name</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $customer->first_name ?? 'John' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Last Name</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $customer->last_name ?? 'Doe' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $customer->email ?? 'john.doe@example.com' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Phone</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $customer->phone ?? '+1 (555) 123-4567' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Date of Birth</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $customer->date_of_birth ? $customer->date_of_birth->format('M d, Y') : 'Not provided' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Gender</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($customer->gender ?? 'Not specified') }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Order History -->
                <div class="bg-white shadow rounded-lg p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-medium text-gray-900">Order History</h2>
                        <a href="{{ route('admin.orders.index') }}?customer_id={{ $customer->id ?? 1 }}" class="text-sm text-indigo-600 hover:text-indigo-500">View all orders</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($customer->orders ?? [] as $order)
                                <tr>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $order->order_number }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $order->created_at->format('M d, Y') }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $order->status == 'delivered' ? 'bg-green-100 text-green-800' : 
                                               ($order->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                               'bg-blue-100 text-blue-800') }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">${{ number_format($order->total_amount, 2) }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm">
                                        <a href="{{ route('admin.orders.show', $order) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-6 text-center text-sm text-gray-500">No orders found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Customer Statistics</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Total Orders</dt>
                            <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $customer->order_count ?? 12 }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Total Spent</dt>
                            <dd class="mt-1 text-3xl font-semibold text-green-600">${{ number_format($customer->total_spent ?? 2456.78, 2) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Average Order</dt>
                            <dd class="mt-1 text-3xl font-semibold text-gray-900">${{ number_format($customer->avg_order_value ?? 204.73, 2) }}</dd>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Status & Group -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Account Status</h2>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">Status</span>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ ($customer->is_active ?? true) ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ ($customer->is_active ?? true) ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">Email Verified</span>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ ($customer->email_verified ?? true) ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ ($customer->email_verified ?? true) ? 'Verified' : 'Unverified' }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">Customer Group</span>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ ($customer->customer_group ?? 'regular') == 'vip' ? 'bg-yellow-100 text-yellow-800' : 
                                   (($customer->customer_group ?? 'regular') == 'wholesale' ? 'bg-purple-100 text-purple-800' : 
                                   'bg-gray-100 text-gray-800') }}">
                                {{ ucfirst($customer->customer_group ?? 'regular') }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">Newsletter</span>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ ($customer->accepts_newsletter ?? false) ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ ($customer->accepts_newsletter ?? false) ? 'Subscribed' : 'Not Subscribed' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Preferences -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Preferences</h2>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Preferred Language</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $customer->preferred_language ?? 'English' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Currency</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $customer->preferred_currency ?? 'USD' }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Metadata -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Account Information</h2>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Registered</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ ($customer->created_at ?? now())->format('M d, Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Last Active</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ ($customer->last_active ?? now())->format('M d, Y h:i A') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Last Order</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $customer->last_order_date ? $customer->last_order_date->format('M d, Y') : 'No orders yet' }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Actions -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h2>
                    <div class="space-y-2">
                        <button class="w-full inline-flex justify-center items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                            Reset Password
                        </button>
                        <button class="w-full inline-flex justify-center items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700">
                            {{ ($customer->is_active ?? true) ? 'Deactivate Account' : 'Activate Account' }}
                        </button>
                        <button class="w-full inline-flex justify-center items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700">
                            Delete Customer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
