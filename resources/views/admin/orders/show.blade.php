@extends('layouts.admin')

@section('title', 'Order Details')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <a href="{{ route('admin.orders.index') }}" class="mr-4">
                        <svg class="h-6 w-6 text-gray-600 hover:text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </a>
                    <h1 class="text-2xl font-semibold text-gray-900">Order #{{ $order->order_number ?? 'ORD-2024-0001' }}</h1>
                </div>
                <div class="flex space-x-3">
                    <button class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        Print Invoice
                    </button>
                    <button class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
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
                <!-- Order Items -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Order Items</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Qty</th>
                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($order->items ?? [] as $item)
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $item->product_name }}</div>
                                        <div class="text-sm text-gray-500">SKU: {{ $item->product_sku }}</div>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900">${{ number_format($item->price, 2) }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $item->quantity }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900 text-right">${{ number_format($item->subtotal, 2) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="text-sm font-medium text-gray-900">Sample Product</div>
                                        <div class="text-sm text-gray-500">SKU: PROD-001</div>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900">$99.99</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">2</td>
                                    <td class="px-4 py-3 text-sm text-gray-900 text-right">$199.98</td>
                                </tr>
                                @endforelse
                            </tbody>
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <td colspan="3" class="px-4 py-3 text-sm font-medium text-gray-900 text-right">Subtotal:</td>
                                    <td class="px-4 py-3 text-sm font-semibold text-gray-900 text-right">${{ number_format($order->subtotal ?? 199.98, 2) }}</td>
                                </tr>
                                @if($order->discount_amount ?? 0 > 0)
                                <tr>
                                    <td colspan="3" class="px-4 py-2 text-sm font-medium text-gray-900 text-right">Discount:</td>
                                    <td class="px-4 py-2 text-sm font-semibold text-green-600 text-right">-${{ number_format($order->discount_amount ?? 0, 2) }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td colspan="3" class="px-4 py-2 text-sm font-medium text-gray-900 text-right">Shipping:</td>
                                    <td class="px-4 py-2 text-sm font-semibold text-gray-900 text-right">${{ number_format($order->shipping_amount ?? 5.00, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="px-4 py-2 text-sm font-medium text-gray-900 text-right">Tax:</td>
                                    <td class="px-4 py-2 text-sm font-semibold text-gray-900 text-right">${{ number_format($order->tax_amount ?? 10.00, 2) }}</td>
                                </tr>
                                <tr class="border-t-2 border-gray-300">
                                    <td colspan="3" class="px-4 py-3 text-lg font-bold text-gray-900 text-right">Total:</td>
                                    <td class="px-4 py-3 text-lg font-bold text-gray-900 text-right">${{ number_format($order->total_amount ?? 214.98, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- Customer Information -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Customer Information</h2>
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Name</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($order->customer)
                                    {{ $order->customer->first_name }} {{ $order->customer->last_name }}
                                @else
                                    John Doe
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($order->customer && $order->customer->email)
                                    {{ $order->customer->email }}
                                @else
                                    john.doe@example.com
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Phone</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($order->customer && $order->customer->phone)
                                    {{ $order->customer->phone }}
                                @else
                                    +1 (555) 123-4567
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Customer Group</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($order->customer && $order->customer->customer_group)
                                    {{ ucfirst($order->customer->customer_group) }}
                                @else
                                    Regular
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>

                <!-- Shipping & Billing -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white shadow rounded-lg p-6">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">Shipping Address</h2>
                        <address class="text-sm text-gray-600 not-italic">
                            {{ is_string($order->shipping_name) ? $order->shipping_name : 'John Doe' }}<br>
                            {{ is_string($order->shipping_address) ? $order->shipping_address : '123 Main Street' }}<br>
                            {{ is_string($order->shipping_city) ? $order->shipping_city : 'New York' }}, 
                            {{ is_string($order->shipping_state) ? $order->shipping_state : 'NY' }} 
                            {{ is_string($order->shipping_zip) ? $order->shipping_zip : '10001' }}<br>
                            {{ is_string($order->shipping_country) ? $order->shipping_country : 'United States' }}
                        </address>
                    </div>

                    <div class="bg-white shadow rounded-lg p-6">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">Billing Address</h2>
                        <address class="text-sm text-gray-600 not-italic">
                            {{ is_string($order->billing_name) ? $order->billing_name : 'John Doe' }}<br>
                            {{ is_string($order->billing_address) ? $order->billing_address : '123 Main Street' }}<br>
                            {{ is_string($order->billing_city) ? $order->billing_city : 'New York' }}, 
                            {{ is_string($order->billing_state) ? $order->billing_state : 'NY' }} 
                            {{ is_string($order->billing_zip) ? $order->billing_zip : '10001' }}<br>
                            {{ is_string($order->billing_country) ? $order->billing_country : 'United States' }}
                        </address>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Order Status -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Order Status</h2>
                    <div class="space-y-3">
                        <div>
                            <label class="text-sm font-medium text-gray-700">Current Status</label>
                            <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option {{ ($order->status ?? 'pending') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option {{ ($order->status ?? '') == 'processing' ? 'selected' : '' }}>Processing</option>
                                <option {{ ($order->status ?? '') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option {{ ($order->status ?? '') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option {{ ($order->status ?? '') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <button class="w-full inline-flex justify-center items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                            Update Status
                        </button>
                    </div>
                </div>

                <!-- Payment Information -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Payment Information</h2>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Payment Method</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($order->payment_method ?? 'credit_card') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Payment Status</dt>
                            <dd class="mt-1">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ ($order->payment_status ?? 'paid') == 'paid' ? 'bg-green-100 text-green-800' : 
                                       (($order->payment_status ?? '') == 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                       'bg-red-100 text-red-800') }}">
                                    {{ ucfirst($order->payment_status ?? 'paid') }}
                                </span>
                            </dd>
                        </div>
                        @if($order->transaction_id)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Transaction ID</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $order->transaction_id }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>

                <!-- Order Timeline -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Order Timeline</h2>
                    <div class="flow-root">
                        <ul role="list" class="-mb-8">
                            <li>
                                <div class="relative pb-8">
                                    <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </span>
                                        </div>
                                        <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                            <div>
                                                <p class="text-sm text-gray-900">Order placed</p>
                                            </div>
                                            <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                {{ ($order->created_at ?? now())->format('M d, h:i A') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="relative pb-8">
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span class="h-8 w-8 rounded-full bg-gray-400 flex items-center justify-center ring-8 ring-white">
                                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </span>
                                        </div>
                                        <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                            <div>
                                                <p class="text-sm text-gray-500">Processing</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Order Notes -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Order Notes</h2>
                    <textarea rows="4" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Add a note...">{{ $order->notes ?? '' }}</textarea>
                    <button class="mt-2 w-full inline-flex justify-center items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                        Save Note
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
