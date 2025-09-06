<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Coupon;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create an admin user for testing
        $this->admin = User::factory()->create();
        $this->actingAs($this->admin);
    }

    public function test_orders_index_page_can_be_rendered(): void
    {
        $response = $this->get(route('admin.orders.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.orders.index');
    }

    public function test_can_view_order_details(): void
    {
        $customer = Customer::factory()->create();
        $order = Order::factory()->create(['customer_id' => $customer->id]);

        $response = $this->get(route('admin.orders.show', $order));

        $response->assertStatus(200);
        $response->assertViewIs('admin.orders.show');
        $response->assertSee($order->order_number);
    }

    public function test_can_update_order_status(): void
    {
        $order = Order::factory()->create(['status' => 'pending']);

        $response = $this->patch(route('admin.orders.update-status', $order), [
            'status' => 'processing',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'processing',
        ]);
    }

    public function test_can_update_payment_status(): void
    {
        $order = Order::factory()->create(['payment_status' => 'pending']);

        $response = $this->patch(route('admin.orders.update-payment', $order), [
            'payment_status' => 'paid',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'payment_status' => 'paid',
        ]);
    }

    public function test_can_add_tracking_number(): void
    {
        $order = Order::factory()->create([
            'status' => 'shipped',
            'tracking_number' => null,
        ]);

        $response = $this->patch(route('admin.orders.update-tracking', $order), [
            'tracking_number' => 'ABC123456789',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'tracking_number' => 'ABC123456789',
        ]);
    }

    public function test_can_filter_orders_by_status(): void
    {
        Order::factory()->count(3)->create(['status' => 'pending']);
        Order::factory()->count(2)->create(['status' => 'delivered']);

        $response = $this->get(route('admin.orders.index', ['status' => 'pending']));

        $response->assertStatus(200);
        $orders = $response->viewData('orders');
        
        foreach ($orders as $order) {
            $this->assertEquals('pending', $order->status);
        }
    }

    public function test_can_filter_orders_by_date_range(): void
    {
        Order::factory()->create(['created_at' => now()->subDays(10)]);
        Order::factory()->create(['created_at' => now()->subDays(5)]);
        Order::factory()->create(['created_at' => now()->subDay()]);

        $response = $this->get(route('admin.orders.index', [
            'date_from' => now()->subDays(7)->format('Y-m-d'),
            'date_to' => now()->format('Y-m-d'),
        ]));

        $response->assertStatus(200);
        $orders = $response->viewData('orders');
        
        $this->assertCount(2, $orders);
    }

    public function test_can_search_orders_by_number(): void
    {
        Order::factory()->create(['order_number' => 'ORD-123456']);
        Order::factory()->create(['order_number' => 'ORD-789012']);

        $response = $this->get(route('admin.orders.index', ['search' => '123456']));

        $response->assertStatus(200);
        $response->assertSee('ORD-123456');
        $response->assertDontSee('ORD-789012');
    }

    public function test_can_search_orders_by_customer(): void
    {
        $customer1 = Customer::factory()->create(['email' => 'john@example.com']);
        $customer2 = Customer::factory()->create(['email' => 'jane@example.com']);
        
        Order::factory()->create(['customer_id' => $customer1->id]);
        Order::factory()->create(['customer_id' => $customer2->id]);

        $response = $this->get(route('admin.orders.index', ['customer' => 'john']));

        $response->assertStatus(200);
        $response->assertSee('john@example.com');
        $response->assertDontSee('jane@example.com');
    }

    public function test_can_cancel_order(): void
    {
        $order = Order::factory()->create(['status' => 'pending']);

        $response = $this->post(route('admin.orders.cancel', $order), [
            'reason' => 'Customer request',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'cancelled',
        ]);
    }

    public function test_can_apply_coupon_to_order(): void
    {
        $order = Order::factory()->create([
            'subtotal' => 100.00,
            'total_amount' => 110.00,
            'coupon_code' => null,
        ]);
        
        $coupon = Coupon::factory()->create([
            'code' => 'SAVE10',
            'discount_type' => 'percentage',
            'discount_value' => 10,
            'is_active' => true,
        ]);

        $response = $this->post(route('admin.orders.apply-coupon', $order), [
            'coupon_code' => 'SAVE10',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'coupon_code' => 'SAVE10',
        ]);
    }

    public function test_can_add_admin_notes(): void
    {
        $order = Order::factory()->create(['admin_notes' => null]);

        $response = $this->patch(route('admin.orders.update-notes', $order), [
            'admin_notes' => 'Important customer - handle with care',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'admin_notes' => 'Important customer - handle with care',
        ]);
    }

    public function test_can_generate_invoice(): void
    {
        $customer = Customer::factory()->create();
        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'status' => 'delivered',
        ]);

        $product = Product::factory()->create();
        $order->items()->create([
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => $product->price,
            'subtotal' => $product->price * 2,
        ]);

        $response = $this->get(route('admin.orders.invoice', $order));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    public function test_can_bulk_update_order_status(): void
    {
        $orders = Order::factory()->count(5)->create(['status' => 'pending']);
        $orderIds = $orders->pluck('id')->toArray();

        $response = $this->post(route('admin.orders.bulk-update'), [
            'order_ids' => $orderIds,
            'status' => 'processing',
        ]);

        $response->assertStatus(200);
        
        foreach ($orderIds as $id) {
            $this->assertDatabaseHas('orders', [
                'id' => $id,
                'status' => 'processing',
            ]);
        }
    }

    public function test_order_total_calculation(): void
    {
        $order = Order::factory()->create([
            'subtotal' => 100.00,
            'tax_amount' => 10.00,
            'shipping_amount' => 5.00,
            'discount_amount' => 15.00,
        ]);

        $expectedTotal = 100.00 + 10.00 + 5.00 - 15.00;
        
        $this->assertEquals($expectedTotal, $order->total_amount);
    }

    public function test_cannot_ship_cancelled_order(): void
    {
        $order = Order::factory()->create(['status' => 'cancelled']);

        $response = $this->patch(route('admin.orders.update-status', $order), [
            'status' => 'shipped',
        ]);

        $response->assertSessionHasErrors();
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'cancelled',
        ]);
    }

    public function test_order_statistics_calculation(): void
    {
        $customer = Customer::factory()->create();
        
        Order::factory()->count(3)->create([
            'customer_id' => $customer->id,
            'total_amount' => 100.00,
            'status' => 'delivered',
        ]);

        $customer->refresh();

        $this->assertEquals(3, $customer->order_count);
        $this->assertEquals(300.00, $customer->total_spent);
    }

    public function test_recent_orders_display(): void
    {
        Order::factory()->count(10)->create();

        $response = $this->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('recentOrders');
        
        $recentOrders = $response->viewData('recentOrders');
        $this->assertLessThanOrEqual(5, count($recentOrders));
    }

    public function test_export_orders_to_csv(): void
    {
        Order::factory()->count(5)->create();

        $response = $this->get(route('admin.orders.export', ['format' => 'csv']));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv');
    }
}
