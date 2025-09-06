<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductTest extends TestCase
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

    public function test_products_index_page_can_be_rendered(): void
    {
        $response = $this->get(route('admin.products.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.products.index');
    }

    public function test_can_create_product(): void
    {
        $category = Category::factory()->create();
        $tag = Tag::factory()->create();

        $productData = [
            'name' => 'Test Product',
            'slug' => 'test-product',
            'sku' => 'TST-001',
            'description' => 'Test product description',
            'short_description' => 'Short description',
            'price' => 99.99,
            'cost_price' => 45.00,
            'sale_price' => 79.99,
            'stock_quantity' => 100,
            'low_stock_threshold' => 10,
            'track_quantity' => true,
            'in_stock' => true,
            'is_featured' => false,
            'is_active' => true,
            'weight' => '1.5kg',
            'dimensions' => '10x10x10cm',
            'categories' => [$category->id],
            'tags' => [$tag->id],
        ];

        $response = $this->post(route('admin.products.store'), $productData);

        $response->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            'sku' => 'TST-001',
        ]);
    }

    public function test_can_view_product(): void
    {
        $product = Product::factory()->create();

        $response = $this->get(route('admin.products.show', $product));

        $response->assertStatus(200);
        $response->assertViewIs('admin.products.show');
        $response->assertSee($product->name);
    }

    public function test_can_update_product(): void
    {
        $product = Product::factory()->create();

        $updatedData = [
            'name' => 'Updated Product Name',
            'slug' => $product->slug,
            'sku' => $product->sku,
            'description' => 'Updated description',
            'short_description' => $product->short_description,
            'price' => 149.99,
            'cost_price' => $product->cost_price,
            'sale_price' => null,
            'stock_quantity' => 50,
            'low_stock_threshold' => $product->low_stock_threshold,
            'track_quantity' => $product->track_quantity,
            'in_stock' => true,
            'is_featured' => true,
            'is_active' => $product->is_active,
            'weight' => $product->weight,
            'dimensions' => $product->dimensions,
        ];

        $response = $this->put(route('admin.products.update', $product), $updatedData);

        $response->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Updated Product Name',
            'price' => 149.99,
            'is_featured' => true,
        ]);
    }

    public function test_can_delete_product(): void
    {
        $product = Product::factory()->create();

        $response = $this->delete(route('admin.products.destroy', $product));

        $response->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_product_search_functionality(): void
    {
        Product::factory()->create(['name' => 'Laptop Computer']);
        Product::factory()->create(['name' => 'Desktop Monitor']);
        Product::factory()->create(['name' => 'Wireless Mouse']);

        $response = $this->get(route('admin.products.index', ['search' => 'Computer']));

        $response->assertStatus(200);
        $response->assertSee('Laptop Computer');
        $response->assertDontSee('Wireless Mouse');
    }

    public function test_product_filter_by_category(): void
    {
        $category1 = Category::factory()->create(['name' => 'Electronics']);
        $category2 = Category::factory()->create(['name' => 'Clothing']);

        $product1 = Product::factory()->create(['name' => 'Smartphone']);
        $product2 = Product::factory()->create(['name' => 'T-Shirt']);

        $product1->categories()->attach($category1);
        $product2->categories()->attach($category2);

        $response = $this->get(route('admin.products.index', ['category' => $category1->id]));

        $response->assertStatus(200);
        $response->assertSee('Smartphone');
        $response->assertDontSee('T-Shirt');
    }

    public function test_product_stock_validation(): void
    {
        $product = Product::factory()->create(['stock_quantity' => 5]);

        $response = $this->put(route('admin.products.update', $product), [
            'stock_quantity' => -10,
        ]);

        $response->assertSessionHasErrors(['stock_quantity']);
    }

    public function test_product_price_validation(): void
    {
        $productData = [
            'name' => 'Test Product',
            'sku' => 'TST-002',
            'price' => -50, // Invalid negative price
        ];

        $response = $this->post(route('admin.products.store'), $productData);

        $response->assertSessionHasErrors(['price']);
    }

    public function test_product_image_upload(): void
    {
        Storage::fake('public');

        $product = Product::factory()->create();
        $file = UploadedFile::fake()->image('product.jpg');

        $response = $this->post(route('admin.products.upload-image', $product), [
            'image' => $file,
        ]);

        $response->assertStatus(200);
        Storage::disk('public')->assertExists('products/' . $file->hashName());
    }

    public function test_out_of_stock_products_filter(): void
    {
        Product::factory()->count(3)->create(['in_stock' => true]);
        Product::factory()->count(2)->create(['in_stock' => false, 'stock_quantity' => 0]);

        $response = $this->get(route('admin.products.index', ['stock' => 'out_of_stock']));

        $response->assertStatus(200);
        $products = $response->viewData('products');
        
        foreach ($products as $product) {
            $this->assertFalse($product->in_stock);
        }
    }

    public function test_low_stock_alert(): void
    {
        Product::factory()->create([
            'name' => 'Low Stock Product',
            'stock_quantity' => 3,
            'low_stock_threshold' => 5,
        ]);

        $response = $this->get(route('admin.products.index', ['stock' => 'low']));

        $response->assertStatus(200);
        $response->assertSee('Low Stock Product');
    }

    public function test_bulk_product_status_update(): void
    {
        $products = Product::factory()->count(5)->create(['is_active' => true]);
        $productIds = $products->pluck('id')->toArray();

        $response = $this->post(route('admin.products.bulk-update'), [
            'product_ids' => $productIds,
            'action' => 'deactivate',
        ]);

        $response->assertStatus(200);
        
        foreach ($productIds as $id) {
            $this->assertDatabaseHas('products', [
                'id' => $id,
                'is_active' => false,
            ]);
        }
    }

    public function test_product_duplicate_sku_validation(): void
    {
        Product::factory()->create(['sku' => 'EXISTING-SKU']);

        $productData = [
            'name' => 'New Product',
            'sku' => 'EXISTING-SKU', // Duplicate SKU
            'price' => 99.99,
        ];

        $response = $this->post(route('admin.products.store'), $productData);

        $response->assertSessionHasErrors(['sku']);
    }
}
