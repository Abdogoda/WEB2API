<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Requests\Product\UploadProductImageRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Services\CategoryService;
use App\Services\ProductService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function __construct(
        protected ProductService $productService,
        protected CategoryService $categoryService
    ) {
    }
    public function index()
    {
        Gate::authorize('viewAny', Product::class);

        $products = $this->productService->listProducts();
        $categories = $this->categoryService->getAllCategories();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function store(StoreProductRequest $request)
    {
        Gate::authorize('create', Product::class);

        $product = Product::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'stock' => $request->stock,
            'active' => $request->active ? true : false,
            'featured' => $request->featured ? true : false
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $imagePath = $image->store('products', 'public');
                $product->images()->create(['path' => $imagePath, 'is_primary' => $index === 0]);
            }
        }

        return back()->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        Gate::authorize('view', $product);

        $product->load(['category', 'images']);
        $simillarProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->inRandomOrder()->limit(6)->get();
        $categories = Category::all();

        return view('admin.products.show', compact('product', 'simillarProducts', 'categories'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        Gate::authorize('update', $product);

        $data = $request->validated();

        $data['slug'] = Str::slug($request->name ?? $product->name);
        $data['active'] = $request->active ? true : false;
        $data['featured'] = $request->featured ? true : false;

        $product->update($data);

        return back()->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        Gate::authorize('delete', $product);

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();
        return back()->with('success', 'Product deleted successfully.');
    }

    public function uploadImages(UploadProductImageRequest $request)
    {
        $product = Product::findOrFail($request->product_id);

        Gate::authorize('update', $product);

        if ($product->images->count() >= 5) {
            return back()->with('error', 'You can only upload 5 images.');
        }

        foreach ($request->file('images') as $index => $image) {
            $imagePath = $image->store('products', 'public');
            $product->images()->create(['path' => $imagePath, 'is_primary' => false]);
        }

        return back()->with('success', 'Images uploaded successfully.');
    }

    public function setPrimary(ProductImage $image)
    {
        $product = $image->product;

        Gate::authorize('update', $product);

        $product->images()->update(['is_primary' => false]);
        $image->update(['is_primary' => true]);

        return back()->with('success', 'Primary image updated successfully.');
    }

    public function deleteImage(ProductImage $image)
    {
        $product = $image->product;
        Gate::authorize('update', $product);

        Storage::disk('public')->delete($image->path);

        if ($image->isPrimary()) {
            $product->images->where('id', '!=', $product->id)->first()->update(['is_primary' => true]);
        }

        $image->delete();

        return redirect()->back()->with('success', 'Image deleted successfully.');
    }
}
