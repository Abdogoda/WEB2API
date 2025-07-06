<?php

namespace App\Http\Controllers\API\V1\User;

use App\Http\Controllers\API\BaseApiController;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;

class CategoryController extends BaseApiController
{

    public function __construct(protected CategoryService $categoryService)
    {
    }

    public function index(): JsonResponse
    {
        $categories = $this->categoryService->getAllCategories();
        return $this->sendResponse(CategoryResource::collection($categories), 'Categories retrieved successfully.');
    }


    public function show(Category $category): JsonResponse
    {
        return $this->sendResponse(
            new CategoryResource($this->categoryService->getCategoryWithActiveProducts($category)),
            'Category retrieved successfully.'
        );
    }
}
