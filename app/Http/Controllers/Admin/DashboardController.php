<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Get statistics
        $totalOrders = Order::count();
        $totalProducts = Product::count();
        $totalCustomers = Customer::count();
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total_amount');
        
        // Get products in stock
        $inStock = Product::where('in_stock', true)->count();
        
        // Get low stock products count
        $lowStockCount = Product::whereColumn('stock_quantity', '<=', 'low_stock_threshold')
            ->where('track_quantity', true)
            ->count();
        
        // Get recent orders (last 5)
        $recentOrders = Order::with('customer')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Get top products by sales
        $topProducts = Product::select('products.*')
            ->selectRaw('(price * sales_count) as revenue')
            ->where('sales_count', '>', 0)
            ->orderBy('sales_count', 'desc')
            ->limit(5)
            ->get();
        
        return view('admin.dashboard', compact(
            'totalOrders',
            'totalProducts',
            'totalCustomers',
            'totalRevenue',
            'inStock',
            'lowStockCount',
            'recentOrders',
            'topProducts'
        ));
    }
}
