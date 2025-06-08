<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();
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

        return view('user.products', compact('products', 'categories'));
    }


    public function category(Category $category)
    {
        $products = $category->products()->where('active', true)->where('stock', '>', 0)->paginate(8);
        return view('user.category', compact('category', 'products'));
    }

    public function product(Product $product)
    {
        $simillarProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->inRandomOrder()->limit(6)->get();

        return view('user.product', compact('product', 'simillarProducts'));
    }
}
