<?php

namespace App\Http\Controllers\API\V1\User;

use App\Http\Controllers\Controller;

use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->get();
        return response()->json([
            'data' => $categories
        ]);
    }


    public function show(Category $category)
    {
        return response()->json([
            'data' => $category->load([
                'products' => function ($query) {
                    $query->where('active', true)->where('stock', '>', 0);
                }
            ])
        ]);
    }
}
