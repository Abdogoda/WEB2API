<?php

namespace App\Services\Admin;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductService
{
  public function listProducts()
  {
    return Product::with(['category', 'images'])->orderBy('created_at', 'desc')->get();
  }

  public function createProduct(array $data, $images = null)
  {
    $data['slug'] = Str::slug($data['name']);
    $product = Product::create($data);
    if ($images) {
      foreach ($images as $index => $image) {
        $imagePath = $image->store('products', 'public');
        $product->images()->create(['path' => $imagePath, 'is_primary' => $index === 0]);
      }
    }
    return $product->load(['category', 'images']);
  }

  public function getProduct(Product $product)
  {
    return $product->load(['category', 'images']);
  }

  public function updateProduct(Product $product, array $data, $images = null)
  {
    $data['slug'] = Str::slug($data['name'] ?? $product->name);
    $product->update($data);
    if ($images) {
      foreach ($images as $index => $image) {
        $imagePath = $image->store('products', 'public');
        $product->images()->create(['path' => $imagePath, 'is_primary' => false]);
      }
    }
    return $product->load(['category', 'images']);
  }

  public function deleteProduct(Product $product)
  {
    if ($product->image) {
      Storage::disk('public')->delete($product->image);
    }
    $product->delete();
  }

  public function simillarProducts(Product $product, int $limit = 6)
  {
    return Product::where('category_id', $product->category_id)
      ->where('id', '!=', $product->id)
      ->inRandomOrder()->limit($limit)->get();
  }

  public function uploadImages(Product $product, array $images)
  {
    $uploadedImages = [];
    foreach ($images as $index => $image) {
      $imagePath = $image->store('products', 'public');
      $uploadedImages[] = $product->images()->create(['path' => $imagePath, 'is_primary' => false]);
    }

    return $uploadedImages;
  }

  public function setPrimaryImage(ProductImage $image)
  {
    $product = $image->product;
    $product->images()->update(['is_primary' => false]);
    $image->update(['is_primary' => true]);
    return $image;
  }

  public function deleteImage(ProductImage $image)
  {
    $product = $image->product;
    Storage::disk('public')->delete($image->path);
    if ($image->isPrimary()) {
      $product->images->where('id', '!=', $product->id)->first()?->update(['is_primary' => true]);
    }
    $image->delete();
  }
}
