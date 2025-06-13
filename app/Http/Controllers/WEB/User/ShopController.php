<?php

namespace App\Http\Controllers\WEB\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ListProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Services\CategoryService;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ShopController extends Controller
{

    public function __construct(
        protected CategoryService $categoryService,
        protected ProductService $productService
    ) {

    }

    public function index(ListProductRequest $request)
    {
        $categories = $this->categoryService->getAllCategories();
        $products = $this->productService->getFilteredProducts($request->all());

        return view('user.products', compact('products', 'categories'));
    }


    public function category(Category $category)
    {
        $products = $this->productService->getProductByCategory($category->id);
        return view('user.category', compact('category', 'products'));
    }

    public function product(Product $product)
    {
        $simillarProducts = $this->productService->simillarProducts($product);

        return view('user.product', compact('product', 'simillarProducts'));
    }
}
