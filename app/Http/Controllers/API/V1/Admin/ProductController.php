<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\API\BaseApiController;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends BaseApiController
{
    public function index(): JsonResponse
    {
        $products = Product::with(['category', 'images'])->orderBy('created_at', 'desc')->get();
        return $this->sendResponse($products, 'Products retrieved successfully.');
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:products,name',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|gt:0',
            'stock' => 'required|numeric|gt:0',
            'active' => 'nullable|in:on,off',
            'featured' => 'nullable|in:on,off',
            'images.*' => 'required|image|mimes:jpg,png,jpeg,gif|max:2048',
        ]);

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

        return $this->sendResponse($product, 'Product created successfully', 201);
    }

    public function show(Product $product): JsonResponse
    {
        return $this->sendResponse($product->load(['category', 'images']), 'Product retrieved successfully.');
    }

    public function simillerProducts(Product $product): JsonResponse
    {
        $simillarProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->inRandomOrder()->limit(6)->get();

        return $this->sendResponse($simillarProducts, 'Simillar products fetched successfully.');
    }

    public function update(Request $request, Product $product): JsonResponse
    {
        $data = $request->validate([
            'name' => 'sometimes|string|max:255|unique:products,name,' . $product->id,
            'description' => 'nullable|string',
            'category_id' => 'sometimes|exists:categories,id',
            'price' => 'sometimes|numeric|gt:0',
            'stock' => 'sometimes|numeric|gt:0',
            'active' => 'nullable|in:on,off',
            'featured' => 'nullable|in:on,off',
        ]);

        $data['slug'] = Str::slug($request->name ?? $product->name);
        $data['active'] = $request->active ? true : false;
        $data['featured'] = $request->featured ? true : false;


        $product->update($data);

        return $this->sendResponse($product, 'Product updated successfully.');
    }

    public function destroy(Product $product): JsonResponse
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();
        return $this->sendResponse(message: 'Product deleted successfully.');
    }

    public function uploadImages(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'images.*' => 'required|image|mimes:jpg,png,jpeg,gif|max:2048',
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($product->images->count() >= 5) {
            return $this->sendError('You can only upload 5 images.', 422);
        }

        foreach ($request->file('images') as $index => $image) {
            $imagePath = $image->store('products', 'public');
            $product->images()->create(['path' => $imagePath, 'is_primary' => false]);
        }

        return $this->sendResponse(message: 'Images uploaded successfully.');
    }

    public function setPrimary(ProductImage $image): JsonResponse
    {
        $product = $image->product;

        $product->images()->update(['is_primary' => false]);
        $image->update(['is_primary' => true]);

        return $this->sendResponse($image, 'Primary image set successfully.');
    }

    public function deleteImage(ProductImage $image): JsonResponse
    {
        $product = $image->product;
        Storage::disk('public')->delete($image->path);

        if ($image->isPrimary()) {
            $product->images->where('id', '!=', $product->id)->first()->update(['is_primary' => true]);
        }

        $image->delete();

        return $this->sendResponse(message: 'Image deleted successfully.');
    }
}
