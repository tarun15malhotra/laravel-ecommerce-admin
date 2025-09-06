<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        $query = Coupon::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        // Valid filter
        if ($request->filled('valid_status')) {
            $now = now();
            if ($request->valid_status == 'valid') {
                $query->where('is_active', true)
                    ->where(function ($q) use ($now) {
                        $q->whereNull('valid_from')->orWhere('valid_from', '<=', $now);
                    })
                    ->where(function ($q) use ($now) {
                        $q->whereNull('valid_until')->orWhere('valid_until', '>=', $now);
                    });
            } elseif ($request->valid_status == 'expired') {
                $query->where('valid_until', '<', $now);
            }
        }

        // Sorting
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $coupons = $query->paginate(20);

        return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        $products = Product::where('is_active', true)->get();

        return view('admin.coupons.create', compact('categories', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:coupons,code|max:50',
            'description' => 'nullable|string|max:255',
            'discount_type' => 'required|in:fixed,percentage',
            'discount_value' => 'required|numeric|min:0',
            'minimum_order_amount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'usage_limit_per_customer' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after_or_equal:valid_from',
            'applicable_categories' => 'array',
            'applicable_categories.*' => 'exists:categories,id',
            'applicable_products' => 'array',
            'applicable_products.*' => 'exists:products,id',
        ]);

        if ($validated['discount_type'] === 'percentage') {
            $validated['discount_value'] = min($validated['discount_value'], 100);
        }

        $validated['code'] = strtoupper($validated['code']);
        $validated['applicable_categories'] = $request->applicable_categories ?? [];
        $validated['applicable_products'] = $request->applicable_products ?? [];

        $coupon = Coupon::create($validated);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon created successfully.');
    }

    public function show(Coupon $coupon)
    {
        $coupon->load('orders.customer');
        
        $stats = [
            'total_usage' => $coupon->usage_count,
            'total_discount' => $coupon->orders()->sum('discount_amount'),
            'average_order_value' => $coupon->orders()->avg('total_amount'),
            'unique_customers' => $coupon->orders()->distinct('customer_id')->count('customer_id'),
        ];

        return view('admin.coupons.show', compact('coupon', 'stats'));
    }

    public function edit(Coupon $coupon)
    {
        $categories = Category::where('is_active', true)->get();
        $products = Product::where('is_active', true)->get();

        return view('admin.coupons.edit', compact('coupon', 'categories', 'products'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'description' => 'nullable|string|max:255',
            'discount_type' => 'required|in:fixed,percentage',
            'discount_value' => 'required|numeric|min:0',
            'minimum_order_amount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'usage_limit_per_customer' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after_or_equal:valid_from',
            'applicable_categories' => 'array',
            'applicable_categories.*' => 'exists:categories,id',
            'applicable_products' => 'array',
            'applicable_products.*' => 'exists:products,id',
        ]);

        if ($validated['discount_type'] === 'percentage') {
            $validated['discount_value'] = min($validated['discount_value'], 100);
        }

        $validated['code'] = strtoupper($validated['code']);
        $validated['applicable_categories'] = $request->applicable_categories ?? [];
        $validated['applicable_products'] = $request->applicable_products ?? [];

        $coupon->update($validated);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon updated successfully.');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon deleted successfully.');
    }

    public function toggleStatus(Coupon $coupon)
    {
        $coupon->update(['is_active' => !$coupon->is_active]);

        return redirect()->back()
            ->with('success', 'Coupon status updated successfully.');
    }

    public function duplicate(Coupon $coupon)
    {
        $newCoupon = $coupon->replicate();
        $newCoupon->code = $coupon->code . '_COPY';
        $newCoupon->usage_count = 0;
        $newCoupon->save();

        return redirect()->route('admin.coupons.edit', $newCoupon)
            ->with('success', 'Coupon duplicated successfully. Please update the code.');
    }
}
