<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\ReportsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Products
    Route::resource('products', ProductController::class);
    Route::post('products/{product}/upload-image', [ProductController::class, 'uploadImage'])->name('products.upload-image');
    Route::post('products/bulk-update', [ProductController::class, 'bulkUpdate'])->name('products.bulk-update');
    Route::post('products/import', [ProductController::class, 'import'])->name('products.import');
    Route::get('products/export/{format}', [ProductController::class, 'export'])->name('products.export');
    
    // Categories
    Route::resource('categories', CategoryController::class);
    Route::post('categories/bulk-update', [CategoryController::class, 'bulkUpdate'])->name('categories.bulk-update');
    
    // Orders
    Route::resource('orders', OrderController::class)->except(['create', 'store']);
    Route::patch('orders/{order}/update-status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::patch('orders/{order}/update-payment', [OrderController::class, 'updatePayment'])->name('orders.update-payment');
    Route::patch('orders/{order}/update-tracking', [OrderController::class, 'updateTracking'])->name('orders.update-tracking');
    Route::patch('orders/{order}/update-notes', [OrderController::class, 'updateNotes'])->name('orders.update-notes');
    Route::post('orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::post('orders/{order}/apply-coupon', [OrderController::class, 'applyCoupon'])->name('orders.apply-coupon');
    Route::get('orders/{order}/invoice', [OrderController::class, 'invoice'])->name('orders.invoice');
    Route::post('orders/bulk-update', [OrderController::class, 'bulkUpdate'])->name('orders.bulk-update');
    Route::get('orders/export/{format}', [OrderController::class, 'export'])->name('orders.export');
    
    // Customers
    Route::resource('customers', CustomerController::class);
    Route::patch('customers/{customer}/activate', [CustomerController::class, 'activate'])->name('customers.activate');
    Route::patch('customers/{customer}/deactivate', [CustomerController::class, 'deactivate'])->name('customers.deactivate');
    Route::get('customers/{customer}/orders', [CustomerController::class, 'orders'])->name('customers.orders');
    Route::post('customers/{customer}/send-email', [CustomerController::class, 'sendEmail'])->name('customers.send-email');
    Route::patch('customers/{customer}/preferences', [CustomerController::class, 'updatePreferences'])->name('customers.preferences');
    Route::post('customers/{customer}/reset-password', [CustomerController::class, 'resetPassword'])->name('customers.reset-password');
    Route::get('customers/top/list', [CustomerController::class, 'topCustomers'])->name('customers.top');
    Route::post('customers/bulk-update', [CustomerController::class, 'bulkUpdate'])->name('customers.bulk-update');
    Route::get('customers/export/{format}', [CustomerController::class, 'export'])->name('customers.export');
    
    // Coupons
    Route::resource('coupons', CouponController::class);
    Route::patch('coupons/{coupon}/toggle-status', [CouponController::class, 'toggleStatus'])->name('coupons.toggle-status');
    Route::post('coupons/bulk-update', [CouponController::class, 'bulkUpdate'])->name('coupons.bulk-update');
    
    // Settings
    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::patch('settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::post('settings/clear-cache', [SettingsController::class, 'clearCache'])->name('settings.clear-cache');
    
    // Reports
    Route::get('reports', [ReportsController::class, 'index'])->name('reports.index');
    Route::get('reports/sales', [ReportsController::class, 'sales'])->name('reports.sales');
    Route::get('reports/products', [ReportsController::class, 'products'])->name('reports.products');
    Route::get('reports/customers', [ReportsController::class, 'customers'])->name('reports.customers');
    Route::get('reports/export/{type}/{format}', [ReportsController::class, 'export'])->name('reports.export');
});

// Health check route for CI/CD
Route::get('/health', function () {
    return response()->json([
        'status' => 'healthy',
        'timestamp' => now()->toIso8601String(),
    ]);
})->name('health');

require __DIR__.'/auth.php';
