<?php

namespace App\Http\Controllers\API\V1\User;

use App\Http\Controllers\API\BaseApiController;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryController extends BaseApiController
{
    public function index(): JsonResponse
    {
        $categories = Category::withCount('products')->get();
        return $this->sendResponse(CategoryResource::collection($categories), 'Categories retrieved successfully.');
    }


    public function show(Category $category): JsonResponse
    {
        $category->load([
            'products' => function ($query) {
                $query->where('active', true)->where('stock', '>', 0);
            }
        ]);

        return $this->sendResponse(
            new CategoryResource($category),
            'Category retrieved successfully.'
        );
    }
}
