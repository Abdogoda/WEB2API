<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\API\BaseApiController;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Requests\Product\UploadProductImageRequest;
use App\Http\Resources\ProductImageResource;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\ProductImage;
use App\Services\Admin\ProductService;
use Illuminate\Http\JsonResponse;

class ProductController extends BaseApiController
{
    public function __construct(protected ProductService $productService)
    {
    }

    public function index(): JsonResponse
    {
        $products = $this->productService->listProducts();
        return $this->sendResponse(ProductResource::collection($products), 'Products retrieved successfully.');
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        $data = $request->validated();
        $images = $request->file('images', []);
        $product = $this->productService->createProduct($data, $images);
        return $this->sendResponse(new ProductResource($product), 'Product created successfully', 201);
    }

    public function show(Product $product): JsonResponse
    {
        $product = $this->productService->getProduct($product);
        return $this->sendResponse(new ProductResource($product), 'Product retrieved successfully.');
    }

    public function simillerProducts(Product $product): JsonResponse
    {
        $simillarProducts = $this->productService->simillarProducts($product);
        return $this->sendResponse(ProductResource::collection($simillarProducts), 'Simillar products fetched successfully.');
    }

    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        $data = $request->validated();
        $images = $request->file('images', []);
        $product = $this->productService->updateProduct($product, $data, $images);
        return $this->sendResponse(new ProductResource($product), 'Product updated successfully.');
    }

    public function destroy(Product $product): JsonResponse
    {
        $this->productService->deleteProduct($product);
        return $this->sendResponse(message: 'Product deleted successfully.');
    }

    public function uploadImages(UploadProductImageRequest $request): JsonResponse
    {
        $product = Product::findOrFail($request->product_id);

        if ($product->images->count() >= 5) {
            return $this->sendError('You can only upload 5 images.', 422);
        }

        $this->productService->uploadImages($product, $request->file('images'));

        return $this->sendResponse(message: 'Images uploaded successfully.');
    }

    public function setPrimary(ProductImage $image): JsonResponse
    {
        $image = $this->productService->setPrimaryImage($image);
        return $this->sendResponse(new ProductImageResource($image), 'Primary image set successfully.');
    }

    public function deleteImage(ProductImage $image): JsonResponse
    {
        $this->productService->deleteImage($image);
        return $this->sendResponse(message: 'Image deleted successfully.');
    }
}
