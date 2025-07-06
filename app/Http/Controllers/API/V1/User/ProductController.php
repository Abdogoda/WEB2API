<?php

namespace App\Http\Controllers\API\V1\User;

use App\Http\Controllers\API\BaseApiController;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\Product\ListProductRequest;
use App\Services\ProductService;

class ProductController extends BaseApiController
{
    public function __construct(
        protected ProductService $productService
    ) {

    }
    public function index(ListProductRequest $request): JsonResponse
    {
        $products = $this->productService->getFilteredProducts($request->validated());

        return $this->sendResponse(ProductResource::collection($products), 'Products retrieved successfully.');
    }

    public function show(Product $product)
    {
        $product->load(['category', 'images']);
        return $this->sendResponse(new ProductResource($product), 'Product retrieved successfully.');
    }

    public function simillarProducts(Product $product)
    {
        $similarProducts = $this->productService->simillarProducts($product);

        return $this->sendResponse(ProductResource::collection($similarProducts), 'Similar products fetched successfully.');
    }

    public function featuredProducts()
    {
        $featuredProducts = $this->productService->getFeaturedProducts();

        return $this->sendResponse(ProductResource::collection($featuredProducts), 'Featured products fetched successfully.');
    }

    public function latestProducts()
    {
        $latestProducts = $this->productService->getLatestProducts();

        return $this->sendResponse(ProductResource::collection($latestProducts), 'Latest products fetched successfully.');
    }
}
