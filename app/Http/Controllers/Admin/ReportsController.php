<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportsController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }

    public function sales(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->subMonth()->startOfDay());
        $endDate = $request->input('end_date', Carbon::now()->endOfDay());
        $groupBy = $request->input('group_by', 'day'); // day, week, month, year

        // Sales overview
        $salesData = Order::where('status', 'delivered')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as order_count'),
                DB::raw('SUM(total_amount) as total_sales'),
                DB::raw('AVG(total_amount) as average_order_value')
            )
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();

        // Sales by status
        $salesByStatus = Order::whereBetween('created_at', [$startDate, $endDate])
            ->select('status', DB::raw('COUNT(*) as count'), DB::raw('SUM(total_amount) as total'))
            ->groupBy('status')
            ->get();

        // Sales by payment method
        $salesByPayment = Order::where('status', 'delivered')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(total_amount) as total'))
            ->groupBy('payment_method')
            ->get();

        // Top selling products
        $topProducts = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('orders.status', 'delivered')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->select(
                'products.id',
                'products.name',
                'products.sku',
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.subtotal) as total_revenue')
            )
            ->groupBy('products.id', 'products.name', 'products.sku')
            ->orderBy('total_revenue', 'desc')
            ->limit(10)
            ->get();

        // Top customers
        $topCustomers = Order::with('customer')
            ->where('status', 'delivered')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select('customer_id', DB::raw('COUNT(*) as order_count'), DB::raw('SUM(total_amount) as total_spent'))
            ->groupBy('customer_id')
            ->orderBy('total_spent', 'desc')
            ->limit(10)
            ->get();

        // Monthly comparison
        $currentMonthSales = Order::where('status', 'delivered')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total_amount');

        $lastMonthSales = Order::where('status', 'delivered')
            ->whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->sum('total_amount');

        $growthRate = $lastMonthSales > 0 
            ? round((($currentMonthSales - $lastMonthSales) / $lastMonthSales) * 100, 2) 
            : 0;

        $data = compact(
            'salesData',
            'salesByStatus',
            'salesByPayment',
            'topProducts',
            'topCustomers',
            'currentMonthSales',
            'lastMonthSales',
            'growthRate',
            'startDate',
            'endDate'
        );

        if ($request->input('export') === 'pdf') {
            $pdf = Pdf::loadView('admin.reports.sales-pdf', $data);
            return $pdf->download('sales-report.pdf');
        }

        if ($request->input('export') === 'excel') {
            return Excel::download(new \App\Exports\SalesReportExport($data), 'sales-report.xlsx');
        }

        return view('admin.reports.sales', $data);
    }
    
    public function products(Request $request)
    {
        // Get product performance data
        $products = Product::select('products.*')
            ->selectRaw('SUM(order_items.quantity) as units_sold')
            ->selectRaw('SUM(order_items.subtotal) as revenue')
            ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
            ->groupBy('products.id')
            ->orderBy('revenue', 'desc')
            ->paginate(20);
        
        // Get category performance
        $categoryPerformance = Category::select('categories.name')
            ->selectRaw('COUNT(DISTINCT products.id) as product_count')
            ->selectRaw('SUM(order_items.subtotal) as revenue')
            ->leftJoin('product_categories', 'categories.id', '=', 'product_categories.category_id')
            ->leftJoin('products', 'product_categories.product_id', '=', 'products.id')
            ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('revenue', 'desc')
            ->get();
        
        return view('admin.reports.product-performance', compact('products', 'categoryPerformance'));
    }

    public function inventory(Request $request)
    {
        // Low stock products
        $lowStockProducts = Product::where('is_active', true)
            ->where('stock_quantity', '>', 0)
            ->where('stock_quantity', '<=', 10)
            ->orderBy('stock_quantity', 'asc')
            ->get();

        // Out of stock products
        $outOfStockProducts = Product::where('is_active', true)
            ->where(function ($query) {
                $query->where('stock_quantity', 0)
                    ->orWhere('in_stock', false);
            })
            ->get();

        // Stock value by category
        $stockByCategory = DB::table('products')
            ->join('product_categories', 'products.id', '=', 'product_categories.product_id')
            ->join('categories', 'product_categories.category_id', '=', 'categories.id')
            ->where('products.is_active', true)
            ->select(
                'categories.name as category_name',
                DB::raw('COUNT(DISTINCT products.id) as product_count'),
                DB::raw('SUM(products.stock_quantity) as total_stock'),
                DB::raw('SUM(products.stock_quantity * products.price) as stock_value')
            )
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('stock_value', 'desc')
            ->get();

        // Most viewed products
        $mostViewedProducts = Product::where('is_active', true)
            ->orderBy('view_count', 'desc')
            ->limit(20)
            ->get();

        // Stock movement (last 30 days)
        $stockMovement = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('orders.created_at', '>=', Carbon::now()->subDays(30))
            ->where('orders.status', '!=', 'cancelled')
            ->select(
                'products.id',
                'products.name',
                'products.sku',
                DB::raw('SUM(order_items.quantity) as quantity_sold'),
                'products.stock_quantity as current_stock'
            )
            ->groupBy('products.id', 'products.name', 'products.sku', 'products.stock_quantity')
            ->orderBy('quantity_sold', 'desc')
            ->limit(20)
            ->get();

        $data = compact(
            'lowStockProducts',
            'outOfStockProducts',
            'stockByCategory',
            'mostViewedProducts',
            'stockMovement'
        );

        if ($request->input('export') === 'pdf') {
            $pdf = Pdf::loadView('admin.reports.inventory-pdf', $data);
            return $pdf->download('inventory-report.pdf');
        }

        if ($request->input('export') === 'excel') {
            return Excel::download(new \App\Exports\InventoryReportExport($data), 'inventory-report.xlsx');
        }

        return view('admin.reports.inventory', $data);
    }

    public function customers(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->subMonth()->startOfDay());
        $endDate = $request->input('end_date', Carbon::now()->endOfDay());

        // New customers
        $newCustomers = Customer::whereBetween('created_at', [$startDate, $endDate])
            ->count();

        // Active customers (made at least one order)
        $activeCustomers = Customer::whereHas('orders', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        })->count();

        // Customer segments
        $customerSegments = Customer::select(
            'customer_group',
            DB::raw('COUNT(*) as count'),
            DB::raw('AVG(total_spent) as avg_spent'),
            DB::raw('SUM(total_spent) as total_spent')
        )
            ->groupBy('customer_group')
            ->get();

        // Top customers by revenue
        $topCustomersByRevenue = Customer::with('orders')
            ->select('customers.*')
            ->join('orders', 'customers.id', '=', 'orders.customer_id')
            ->where('orders.status', 'delivered')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->groupBy('customers.id')
            ->orderBy(DB::raw('SUM(orders.total_amount)'), 'desc')
            ->limit(20)
            ->get();

        // Customer retention rate
        $returningCustomers = Customer::whereHas('orders', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        })->whereHas('orders', function ($query) use ($startDate) {
            $query->where('created_at', '<', $startDate);
        })->count();

        $retentionRate = $activeCustomers > 0
            ? round(($returningCustomers / $activeCustomers) * 100, 2)
            : 0;

        // Customer lifetime value
        $avgLifetimeValue = Customer::where('total_spent', '>', 0)->avg('total_spent');

        // Geographic distribution - country column doesn't exist, using customer groups instead
        $customersByCountry = Customer::select('customer_group', DB::raw('COUNT(*) as count'))
            ->whereNotNull('customer_group')
            ->groupBy('customer_group')
            ->orderBy('count', 'desc')
            ->get();

        $data = compact(
            'newCustomers',
            'activeCustomers',
            'customerSegments',
            'topCustomersByRevenue',
            'returningCustomers',
            'retentionRate',
            'avgLifetimeValue',
            'customersByCountry',
            'startDate',
            'endDate'
        );

        if ($request->input('export') === 'pdf') {
            $pdf = Pdf::loadView('admin.reports.customers-pdf', $data);
            return $pdf->download('customers-report.pdf');
        }

        if ($request->input('export') === 'excel') {
            return Excel::download(new \App\Exports\CustomersReportExport($data), 'customers-report.xlsx');
        }

        return view('admin.reports.customers', $data);
    }

    public function performance(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->subMonth()->startOfDay());
        $endDate = $request->input('end_date', Carbon::now()->endOfDay());

        // Product performance
        $productPerformance = DB::table('products')
            ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
            ->leftJoin('orders', function ($join) use ($startDate, $endDate) {
                $join->on('order_items.order_id', '=', 'orders.id')
                    ->where('orders.status', 'delivered')
                    ->whereBetween('orders.created_at', [$startDate, $endDate]);
            })
            ->select(
                'products.id',
                'products.name',
                'products.sku',
                'products.price',
                'products.cost',
                DB::raw('COALESCE(SUM(order_items.quantity), 0) as units_sold'),
                DB::raw('COALESCE(SUM(order_items.subtotal), 0) as revenue'),
                DB::raw('COALESCE(SUM(order_items.quantity * products.cost), 0) as cost_of_goods'),
                DB::raw('COALESCE(SUM(order_items.subtotal - (order_items.quantity * products.cost)), 0) as profit')
            )
            ->groupBy('products.id', 'products.name', 'products.sku', 'products.price', 'products.cost')
            ->orderBy('revenue', 'desc')
            ->limit(50)
            ->get();

        // Category performance
        $categoryPerformance = DB::table('categories')
            ->leftJoin('product_categories', 'categories.id', '=', 'product_categories.category_id')
            ->leftJoin('order_items', 'product_categories.product_id', '=', 'order_items.product_id')
            ->leftJoin('orders', function ($join) use ($startDate, $endDate) {
                $join->on('order_items.order_id', '=', 'orders.id')
                    ->where('orders.status', 'delivered')
                    ->whereBetween('orders.created_at', [$startDate, $endDate]);
            })
            ->select(
                'categories.id',
                'categories.name',
                DB::raw('COUNT(DISTINCT product_categories.product_id) as product_count'),
                DB::raw('COALESCE(SUM(order_items.quantity), 0) as units_sold'),
                DB::raw('COALESCE(SUM(order_items.subtotal), 0) as revenue')
            )
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('revenue', 'desc')
            ->get();

        // Conversion rates
        $totalViews = Product::sum('view_count');
        $totalOrders = Order::whereBetween('created_at', [$startDate, $endDate])->count();
        $conversionRate = $totalViews > 0 ? round(($totalOrders / $totalViews) * 100, 2) : 0;

        $data = compact(
            'productPerformance',
            'categoryPerformance',
            'totalViews',
            'totalOrders',
            'conversionRate',
            'startDate',
            'endDate'
        );

        if ($request->input('export') === 'pdf') {
            $pdf = Pdf::loadView('admin.reports.performance-pdf', $data);
            return $pdf->download('performance-report.pdf');
        }

        if ($request->input('export') === 'excel') {
            return Excel::download(new \App\Exports\PerformanceReportExport($data), 'performance-report.xlsx');
        }

        return view('admin.reports.performance', $data);
    }
}
