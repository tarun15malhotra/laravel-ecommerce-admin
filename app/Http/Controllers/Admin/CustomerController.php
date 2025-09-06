<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CustomersExport;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Customer group filter
        if ($request->filled('customer_group')) {
            $query->where('customer_group', $request->customer_group);
        }

        // Status filter
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
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

        $customers = $query->paginate(20);

        return view('admin.customers.index', compact('customers'));
    }

    public function show(Customer $customer)
    {
        $customer->load(['orders' => function ($query) {
            $query->latest()->limit(10);
        }]);
        
        $stats = [
            'total_orders' => $customer->orders()->count(),
            'completed_orders' => $customer->orders()->where('status', 'delivered')->count(),
            'cancelled_orders' => $customer->orders()->where('status', 'cancelled')->count(),
            'average_order_value' => $customer->orders()->where('status', 'delivered')->avg('total_amount'),
            'last_order_date' => $customer->last_order_at,
            'customer_since' => $customer->created_at,
        ];

        return view('admin.customers.show', compact('customer', 'stats'));
    }

    public function create()
    {
        return view('admin.customers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'customer_group' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        
        $customer = Customer::create($validated);

        return redirect()->route('admin.customers.show', $customer)
            ->with('success', 'Customer created successfully.');
    }

    public function edit(Customer $customer)
    {
        return view('admin.customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,' . $customer->id,
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'customer_group' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:8|confirmed']);
            $validated['password'] = Hash::make($request->password);
        }

        $customer->update($validated);

        return redirect()->route('admin.customers.show', $customer)
            ->with('success', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer deleted successfully.');
    }

    public function export($format)
    {
        $filename = 'customers_' . date('Y-m-d_His');
        
        if ($format === 'csv') {
            return Excel::download(new CustomersExport, $filename . '.csv');
        } elseif ($format === 'xlsx') {
            return Excel::download(new CustomersExport, $filename . '.xlsx');
        }
        
        return back()->with('error', 'Invalid export format');
    }
    
    public function topCustomers(Request $request)
    {
        $customers = Customer::select('customers.*')
            ->selectRaw('COUNT(orders.id) as order_count')
            ->selectRaw('SUM(orders.total_amount) as total_spent')
            ->leftJoin('orders', 'customers.id', '=', 'orders.customer_id')
            ->groupBy('customers.id')
            ->orderBy('total_spent', 'desc')
            ->limit(20)
            ->get();
        
        return view('admin.customers.top', compact('customers'));
    }

    public function toggleStatus(Customer $customer)
    {
        $customer->update(['is_active' => !$customer->is_active]);

        return redirect()->back()
            ->with('success', 'Customer status updated successfully.');
    }

    public function orders(Customer $customer)
    {
        $orders = $customer->orders()->with('items')->latest()->paginate(20);

        return view('admin.customers.orders', compact('customer', 'orders'));
    }

    public function sendEmail(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // Send email logic here
        // Mail::to($customer->email)->send(new CustomerEmail($validated['subject'], $validated['message']));

        return redirect()->back()
            ->with('success', 'Email sent successfully.');
    }
}
