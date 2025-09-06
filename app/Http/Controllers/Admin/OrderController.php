<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('customer');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhere('tracking_number', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($q) use ($search) {
                        $q->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Payment status filter
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Payment method filter
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Sorting
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $orders = $query->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['customer', 'items.product', 'coupon']);
        return view('admin.orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $order->load(['customer', 'items.product']);
        return view('admin.orders.edit', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled,refunded',
            'payment_status' => 'required|in:pending,paid,failed,refunded',
            'tracking_number' => 'nullable|string',
            'shipping_method' => 'nullable|string',
            'admin_notes' => 'nullable|string',
        ]);

        $oldStatus = $order->status;
        $order->update($validated);

        // Handle status-specific actions
        if ($validated['status'] !== $oldStatus) {
            switch ($validated['status']) {
                case 'shipped':
                    $order->update(['shipped_at' => now()]);
                    // Send shipping notification email
                    break;
                case 'delivered':
                    $order->update(['delivered_at' => now()]);
                    // Update customer stats
                    if ($order->customer) {
                        $order->customer->updateOrderStats();
                    }
                    break;
                case 'cancelled':
                case 'refunded':
                    // Return stock to inventory
                    foreach ($order->items as $item) {
                        if ($item->product) {
                            $item->product->incrementStock($item->quantity);
                        }
                    }
                    break;
            }
        }

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Order updated successfully.');
    }

    public function generateInvoice(Order $order)
    {
        $order->load(['customer', 'items.product']);
        
        $pdf = Pdf::loadView('admin.orders.invoice', compact('order'));
        
        return $pdf->download('invoice-' . $order->order_number . '.pdf');
    }

    public function printInvoice(Order $order)
    {
        $order->load(['customer', 'items.product']);
        
        return view('admin.orders.invoice-print', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled,refunded',
        ]);

        $order->updateStatus($validated['status']);

        return redirect()->back()->with('success', 'Order status updated successfully.');
    }

    public function bulkUpdateStatus(Request $request)
    {
        $validated = $request->validate([
            'order_ids' => 'required|array',
            'order_ids.*' => 'exists:orders,id',
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled,refunded',
        ]);

        Order::whereIn('id', $validated['order_ids'])
            ->update(['status' => $validated['status']]);

        return redirect()->back()->with('success', 'Orders updated successfully.');
    }

    public function create()
    {
        $customers = Customer::where('is_active', true)->get();
        $products = Product::where('is_active', true)->where('in_stock', true)->get();

        return view('admin.orders.create', compact('customers', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|string',
            'shipping_address' => 'required|array',
            'billing_address' => 'required|array',
            'shipping_method' => 'required|string',
            'shipping_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        // Calculate totals
        $subtotal = 0;
        $orderItems = [];
        
        foreach ($validated['items'] as $item) {
            $product = Product::find($item['product_id']);
            $itemSubtotal = $product->current_price * $item['quantity'];
            $subtotal += $itemSubtotal;
            
            $orderItems[] = [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'product_sku' => $product->sku,
                'price' => $product->current_price,
                'quantity' => $item['quantity'],
                'subtotal' => $itemSubtotal,
            ];
            
            // Decrement stock
            $product->decrementStock($item['quantity']);
        }

        // Calculate tax (10% for example)
        $taxAmount = $subtotal * 0.1;
        $totalAmount = $subtotal + $taxAmount + $validated['shipping_amount'];

        // Create order
        $order = Order::create([
            'customer_id' => $validated['customer_id'],
            'status' => 'pending',
            'payment_status' => 'pending',
            'payment_method' => $validated['payment_method'],
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'shipping_amount' => $validated['shipping_amount'],
            'total_amount' => $totalAmount,
            'shipping_address' => $validated['shipping_address'],
            'billing_address' => $validated['billing_address'],
            'shipping_method' => $validated['shipping_method'],
            'notes' => $validated['notes'],
        ]);

        // Create order items
        foreach ($orderItems as $item) {
            $order->items()->create($item);
        }

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Order created successfully.');
    }
}
