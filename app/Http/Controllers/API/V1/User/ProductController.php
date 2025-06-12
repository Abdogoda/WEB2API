<?php

namespace App\Http\Controllers\API\V1\User;

use App\Http\Controllers\Controller;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => 'sometimes|string',
            'category_ids' => 'sometimes|array',
            'category_ids.*' => 'integer|exists:categories,id',
            'min_price' => 'sometimes|numeric|min:0',
            'max_price' => 'sometimes|numeric|min:0',
            'featured' => 'sometimes|boolean',
        ]);
        if ($request->filled('min_price') && $request->filled('max_price')) {
            if ($request->min_price > $request->max_price) {
                return response()->json([
                    'message' => 'min_price cannot be greater than max_price.'
                ], 422);
            }
        }

        $query = Product::query()->where('active', true)->where('stock', '>', 0);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->has('category_ids') && is_array($request->category_ids)) {
            $query->whereIn('category_id', $request->category_ids);
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->has('featured')) {
            $query->where('featured', true);
        }

        $products = $query->paginate(12);

        return response()->json([
            'data' => $products
        ]);
    }

    public function show(Product $product)
    {
        return response()->json([
            'data' => $product
        ]);
    }

    public function simillarProducts(Product $product)
    {
        $similarProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('active', true)
            ->where('stock', '>', 0)
            ->limit(8)
            ->get();

        return response()->json([
            'data' => $similarProducts
        ]);
    }

    public function featuredProducts()
    {
        $featuredProducts = Product::where('active', true)
            ->where('featured', true)
            ->where('stock', '>', 0)
            ->limit(8)
            ->get();

        return response()->json([
            'data' => $featuredProducts
        ]);
    }

    public function latestProducts()
    {
        $latestProducts = Product::where('active', true)
            ->where('stock', '>', 0)
            ->latest()
            ->limit(8)
            ->get();

        return response()->json([
            'data' => $latestProducts
        ]);
    }
}
