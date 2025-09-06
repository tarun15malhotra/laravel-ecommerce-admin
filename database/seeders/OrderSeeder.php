<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Coupon;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = Customer::all();
        $products = Product::where('is_active', true)->get();
        $coupons = Coupon::where('is_active', true)->get();

        // Create orders for each customer
        foreach ($customers as $customer) {
            // Skip some customers to simulate customers without orders
            if (rand(1, 100) <= 20) {
                continue;
            }

            // Create 1-5 orders per customer
            $orderCount = rand(1, 5);
            
            for ($i = 0; $i < $orderCount; $i++) {
                // Create order with random status
                $order = Order::factory()->create([
                    'customer_id' => $customer->id,
                ]);

                // Apply coupon to some orders (30% chance)
                if (rand(1, 100) <= 30 && $coupons->count() > 0) {
                    $coupon = $coupons->random();
                    $order->coupon_code = $coupon->code;
                    
                    // Update discount amount based on coupon
                    if ($coupon->discount_type === 'percentage') {
                        $order->discount_amount = $order->subtotal * ($coupon->discount_value / 100);
                    } else {
                        $order->discount_amount = min($coupon->discount_value, $order->subtotal);
                    }
                    
                    // Increment coupon usage
                    $coupon->increment('usage_count');
                }

                // Create 1-5 order items for each order
                $itemCount = rand(1, 5);
                $subtotal = 0;

                for ($j = 0; $j < $itemCount; $j++) {
                    $product = $products->random();
                    $quantity = rand(1, 3);
                    
                    $orderItem = OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'product_sku' => $product->sku,
                        'price' => $product->price,
                        'quantity' => $quantity,
                        'subtotal' => $product->price * $quantity,
                    ]);
                    
                    $subtotal += $orderItem->subtotal;
                }

                // Update order totals
                $taxAmount = $subtotal * 0.1; // 10% tax
                $discountAmount = $order->discount_amount ?? 0;
                $totalAmount = $subtotal + $taxAmount + $order->shipping_amount - $discountAmount;
                
                $order->update([
                    'subtotal' => $subtotal,
                    'tax_amount' => $taxAmount,
                    'discount_amount' => $discountAmount,
                    'total_amount' => $totalAmount,
                ]);

                // Update customer statistics
                $customer->increment('order_count');
                $customer->increment('total_spent', $totalAmount);
                $customer->update([
                    'last_order_at' => $order->created_at,
                ]);
            }
        }

        // Create some recent pending orders for dashboard
        $recentCustomers = $customers->random(min(10, $customers->count()));
        foreach ($recentCustomers as $customer) {
            $order = Order::factory()->pending()->create([
                'customer_id' => $customer->id,
                'created_at' => now()->subDays(rand(0, 7)),
            ]);

            // Create order items
            $itemCount = rand(1, 3);
            $subtotal = 0;

            for ($i = 0; $i < $itemCount; $i++) {
                $product = $products->random();
                $quantity = rand(1, 2);
                
                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_sku' => $product->sku,
                    'price' => $product->price,
                    'quantity' => $quantity,
                    'subtotal' => $product->price * $quantity,
                ]);
                
                $subtotal += $orderItem->subtotal;
            }

            // Update order totals
            $taxAmount = $subtotal * 0.1;
            $totalAmount = $subtotal + $taxAmount + $order->shipping_amount;
            
            $order->update([
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount,
            ]);
        }
    }
}
