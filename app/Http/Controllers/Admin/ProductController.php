<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductsExport;
use App\Imports\ProductsImport;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['categories', 'tags']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->filled('category_id')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('categories.id', $request->category_id);
            });
        }

        // Stock filter
        if ($request->filled('stock_status')) {
            if ($request->stock_status == 'low') {
                $query->whereRaw('stock_quantity <= low_stock_threshold');
            } elseif ($request->stock_status == 'out') {
                $query->where('stock_quantity', 0);
            }
        }

        // Status filter
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        // Sorting
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $products = $query->paginate(20);
        $categories = Category::where('is_active', true)->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        $tags = Tag::all();

        return view('admin.products.create', compact('categories', 'tags'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku|max:100',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'low_stock_threshold' => 'required|integer|min:0',
            'weight' => 'nullable|string',
            'dimensions' => 'nullable|string',
            'categories' => 'array',
            'categories.*' => 'exists:categories,id',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
            'images' => 'array',
            'images.*' => 'image|max:2048',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'track_quantity' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        
        // Handle image uploads
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                
                // Note: Image processing would be done here with Intervention Image
                // For now, we'll just store the original image
                // To enable image resizing, install: composer require intervention/image
                
                $imagePaths[] = $path;
            }
        }
        $validated['images'] = $imagePaths;

        // Create product
        $product = Product::create($validated);

        // Attach categories and tags
        if (!empty($validated['categories'])) {
            $product->categories()->attach($validated['categories']);
        }
        if (!empty($validated['tags'])) {
            $product->tags()->attach($validated['tags']);
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        $product->load(['categories', 'tags', 'orderItems.order']);
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->get();
        $tags = Tag::all();
        $product->load(['categories', 'tags']);

        return view('admin.products.edit', compact('product', 'categories', 'tags'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:products,sku,' . $product->id,
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'low_stock_threshold' => 'required|integer|min:0',
            'weight' => 'nullable|string',
            'dimensions' => 'nullable|string',
            'categories' => 'array',
            'categories.*' => 'exists:categories,id',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
            'new_images' => 'array',
            'new_images.*' => 'image|max:2048',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'track_quantity' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        
        // Handle new image uploads
        $imagePaths = $product->images ?? [];
        if ($request->hasFile('new_images')) {
            foreach ($request->file('new_images') as $image) {
                $path = $image->store('products', 'public');
                
                // Note: Image processing would be done here with Intervention Image
                // For now, we'll just store the original image
                // To enable image resizing, install: composer require intervention/image
                
                $imagePaths[] = $path;
            }
        }
        $validated['images'] = $imagePaths;

        // Update product
        $product->update($validated);

        // Sync categories and tags
        $product->categories()->sync($validated['categories'] ?? []);
        $product->tags()->sync($validated['tags'] ?? []);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        // Delete images
        if ($product->images) {
            foreach ($product->images as $image) {
                \Storage::disk('public')->delete($image);
            }
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    public function bulkUpdate(Request $request)
    {
        $validated = $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id',
            'action' => 'required|in:activate,deactivate,delete,featured,unfeatured',
        ]);

        $products = Product::whereIn('id', $validated['product_ids']);

        switch ($validated['action']) {
            case 'activate':
                $products->update(['is_active' => true]);
                $message = 'Products activated successfully.';
                break;
            case 'deactivate':
                $products->update(['is_active' => false]);
                $message = 'Products deactivated successfully.';
                break;
            case 'featured':
                $products->update(['is_featured' => true]);
                $message = 'Products marked as featured.';
                break;
            case 'unfeatured':
                $products->update(['is_featured' => false]);
                $message = 'Products unmarked as featured.';
                break;
            case 'delete':
                $products->delete();
                $message = 'Products deleted successfully.';
                break;
            default:
                $message = 'Action completed.';
        }

        return response()->json(['message' => $message], 200);
    }

    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id',
        ]);

        Product::whereIn('id', $validated['product_ids'])->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Products deleted successfully.');
    }

    public function uploadImage(Request $request, Product $product)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            
            // Add image to product's images array
            $images = $product->images ?? [];
            $images[] = $path;
            $product->update(['images' => $images]);
            
            return response()->json([
                'success' => true,
                'path' => $path,
                'message' => 'Image uploaded successfully.'
            ], 200);
        }

        return response()->json(['error' => 'No image uploaded.'], 400);
    }

    public function export($format = 'xlsx')
    {
        $filename = 'products.' . $format;
        
        if ($format === 'csv') {
            return Excel::download(new ProductsExport, $filename, \Maatwebsite\Excel\Excel::CSV);
        }
        
        return Excel::download(new ProductsExport, $filename);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        Excel::import(new ProductsImport, $request->file('file'));

        return redirect()->route('admin.products.index')
            ->with('success', 'Products imported successfully.');
    }

    public function updateStock(Request $request, Product $product)
    {
        $validated = $request->validate([
            'stock_quantity' => 'required|integer|min:0',
            'operation' => 'required|in:set,add,subtract',
        ]);

        if ($validated['operation'] === 'set') {
            $product->stock_quantity = $validated['stock_quantity'];
        } elseif ($validated['operation'] === 'add') {
            $product->stock_quantity += $validated['stock_quantity'];
        } else {
            $product->stock_quantity = max(0, $product->stock_quantity - $validated['stock_quantity']);
        }

        $product->in_stock = $product->stock_quantity > 0;
        $product->save();

        return redirect()->back()->with('success', 'Stock updated successfully.');
    }
}
