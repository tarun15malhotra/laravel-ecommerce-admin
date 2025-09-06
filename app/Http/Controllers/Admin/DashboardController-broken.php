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
                ->where('created_at', '>=', $thisMonth)
                ->sum('total_amount'),
            'totalOrders' => Order::count(),
            'pendingOrders' => Order::where('status', 'pending')->count(),
            'totalCustomers' => Customer::count(),
            'activeCustomers' => Customer::where('is_active', true)->count(),
            'totalProducts' => Product::count(),
            'activeProducts' => Product::where('is_active', true)->count(),
            'lowStockCount' => Product::where('track_quantity', true)
                ->whereRaw('stock_quantity <= low_stock_threshold')
                ->count(),
        ];
    }

    private function getRecentOrders($limit = 10)
    {
        return Order::with('customer')
            ->latest()
            ->take($limit)
            ->get();
    }

    private function getLowStockProducts($limit = 5)
    {
        return Product::where('track_quantity', true)
            ->whereRaw('stock_quantity <= low_stock_threshold')
            ->orderBy('stock_quantity')
            ->take($limit)
            ->get();
    }

    private function getSalesData()
    {
        $days = 30;
        $endDate = Carbon::today();
        $startDate = $endDate->copy()->subDays($days);

        $salesData = Order::where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as total, COUNT(*) as count')
            ->get();

        $dates = [];
        $totals = [];
        $counts = [];

        for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
            $dateStr = $date->format('Y-m-d');
            $dates[] = $date->format('M d');
            
            $dayData = $salesData->firstWhere('date', $dateStr);
            $totals[] = $dayData ? $dayData->total : 0;
            $counts[] = $dayData ? $dayData->count : 0;
        }

        return [
            'dates' => $dates,
            'totals' => $totals,
            'counts' => $counts,
        ];
    }

    private function getTopProducts($limit = 5)
    {
        return Product::orderBy('sales_count', 'desc')
            ->take($limit)
            ->get();
    }

    private function getTopCustomers($limit = 5)
    {
        return Customer::orderBy('total_spent', 'desc')
            ->take($limit)
            ->get();
    }
}
