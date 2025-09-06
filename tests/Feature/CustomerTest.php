<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerTest extends TestCase
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

    public function test_customers_index_page_can_be_rendered(): void
    {
        $response = $this->get(route('admin.customers.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.customers.index');
    }

    public function test_can_view_customer_profile(): void
    {
        $customer = Customer::factory()->create();

        $response = $this->get(route('admin.customers.show', $customer));

        $response->assertStatus(200);
        $response->assertViewIs('admin.customers.show');
        $response->assertSee($customer->email);
    }

    public function test_can_create_customer(): void
    {
        $customerData = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'phone' => '+1234567890',
            'date_of_birth' => '1990-01-01',
            'gender' => 'male',
            'password' => 'password123',
            'customer_group' => 'regular',
            'is_active' => true,
        ];

        $response = $this->post(route('admin.customers.store'), $customerData);

        $response->assertRedirect(route('admin.customers.index'));
        $this->assertDatabaseHas('customers', [
            'email' => 'john.doe@example.com',
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);
    }

    public function test_can_update_customer(): void
    {
        $customer = Customer::factory()->create();

        $updatedData = [
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => $customer->email,
            'phone' => '+9876543210',
            'customer_group' => 'vip',
        ];

        $response = $this->put(route('admin.customers.update', $customer), $updatedData);

        $response->assertRedirect(route('admin.customers.index'));
        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'customer_group' => 'vip',
        ]);
    }

    public function test_can_deactivate_customer(): void
    {
        $customer = Customer::factory()->create(['is_active' => true]);

        $response = $this->patch(route('admin.customers.deactivate', $customer));

        $response->assertRedirect();
        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'is_active' => false,
        ]);
    }

    public function test_can_activate_customer(): void
    {
        $customer = Customer::factory()->create(['is_active' => false]);

        $response = $this->patch(route('admin.customers.activate', $customer));

        $response->assertRedirect();
        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'is_active' => true,
        ]);
    }

    public function test_can_search_customers_by_email(): void
    {
        Customer::factory()->create(['email' => 'alice@example.com']);
        Customer::factory()->create(['email' => 'bob@example.com']);

        $response = $this->get(route('admin.customers.index', ['search' => 'alice']));

        $response->assertStatus(200);
        $response->assertSee('alice@example.com');
        $response->assertDontSee('bob@example.com');
    }

    public function test_can_search_customers_by_name(): void
    {
        Customer::factory()->create(['first_name' => 'Alice', 'last_name' => 'Johnson']);
        Customer::factory()->create(['first_name' => 'Bob', 'last_name' => 'Smith']);

        $response = $this->get(route('admin.customers.index', ['search' => 'Johnson']));

        $response->assertStatus(200);
        $response->assertSee('Alice Johnson');
        $response->assertDontSee('Bob Smith');
    }

    public function test_can_filter_customers_by_group(): void
    {
        Customer::factory()->count(3)->create(['customer_group' => 'regular']);
        Customer::factory()->count(2)->create(['customer_group' => 'vip']);

        $response = $this->get(route('admin.customers.index', ['group' => 'vip']));

        $response->assertStatus(200);
        $customers = $response->viewData('customers');
        
        foreach ($customers as $customer) {
            $this->assertEquals('vip', $customer->customer_group);
        }
    }

    public function test_can_filter_verified_customers(): void
    {
        Customer::factory()->count(3)->create(['email_verified' => true]);
        Customer::factory()->count(2)->create(['email_verified' => false]);

        $response = $this->get(route('admin.customers.index', ['verified' => 'true']));

        $response->assertStatus(200);
        $customers = $response->viewData('customers');
        
        foreach ($customers as $customer) {
            $this->assertTrue($customer->email_verified);
        }
    }

    public function test_can_view_customer_orders(): void
    {
        $customer = Customer::factory()->create();
        Order::factory()->count(5)->create(['customer_id' => $customer->id]);

        $response = $this->get(route('admin.customers.orders', $customer));

        $response->assertStatus(200);
        $orders = $response->viewData('orders');
        $this->assertCount(5, $orders);
    }

    public function test_customer_statistics_display(): void
    {
        $customer = Customer::factory()->create([
            'order_count' => 10,
            'total_spent' => 1500.00,
        ]);

        $response = $this->get(route('admin.customers.show', $customer));

        $response->assertStatus(200);
        $response->assertSee('10'); // Order count
        $response->assertSee('1500.00'); // Total spent
    }

    public function test_can_export_customers_to_csv(): void
    {
        Customer::factory()->count(10)->create();

        $response = $this->get(route('admin.customers.export', ['format' => 'csv']));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv');
    }

    public function test_can_send_email_to_customer(): void
    {
        $customer = Customer::factory()->create();

        $response = $this->post(route('admin.customers.send-email', $customer), [
            'subject' => 'Test Email',
            'message' => 'This is a test email message.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
    }

    public function test_duplicate_email_validation(): void
    {
        Customer::factory()->create(['email' => 'existing@example.com']);

        $customerData = [
            'first_name' => 'New',
            'last_name' => 'Customer',
            'email' => 'existing@example.com', // Duplicate email
            'password' => 'password123',
        ];

        $response = $this->post(route('admin.customers.store'), $customerData);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_can_update_customer_preferences(): void
    {
        $customer = Customer::factory()->create();

        $response = $this->patch(route('admin.customers.preferences', $customer), [
            'preferences' => [
                'newsletter' => true,
                'sms_notifications' => false,
                'marketing_emails' => true,
            ],
        ]);

        $response->assertRedirect();
        $customer->refresh();
        
        $this->assertTrue($customer->preferences['newsletter']);
        $this->assertFalse($customer->preferences['sms_notifications']);
        $this->assertTrue($customer->preferences['marketing_emails']);
    }

    public function test_can_reset_customer_password(): void
    {
        $customer = Customer::factory()->create();

        $response = $this->post(route('admin.customers.reset-password', $customer));

        $response->assertRedirect();
        $response->assertSessionHas('success');
        // In a real application, we would verify that a password reset email was sent
    }

    public function test_can_view_top_customers(): void
    {
        Customer::factory()->create(['total_spent' => 5000.00, 'first_name' => 'Top']);
        Customer::factory()->create(['total_spent' => 3000.00]);
        Customer::factory()->count(8)->create(['total_spent' => 100.00]);

        $response = $this->get(route('admin.customers.top'));

        $response->assertStatus(200);
        $response->assertSee('Top');
        
        $customers = $response->viewData('customers');
        $this->assertLessThanOrEqual(10, count($customers));
        
        // Check that customers are sorted by total_spent
        $previousSpent = PHP_INT_MAX;
        foreach ($customers as $customer) {
            $this->assertLessThanOrEqual($previousSpent, $customer->total_spent);
            $previousSpent = $customer->total_spent;
        }
    }

    public function test_bulk_customer_group_update(): void
    {
        $customers = Customer::factory()->count(5)->create(['customer_group' => 'regular']);
        $customerIds = $customers->pluck('id')->toArray();

        $response = $this->post(route('admin.customers.bulk-update'), [
            'customer_ids' => $customerIds,
            'customer_group' => 'vip',
        ]);

        $response->assertStatus(200);
        
        foreach ($customerIds as $id) {
            $this->assertDatabaseHas('customers', [
                'id' => $id,
                'customer_group' => 'vip',
            ]);
        }
    }
}
