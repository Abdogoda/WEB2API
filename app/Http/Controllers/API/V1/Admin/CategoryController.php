<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\API\BaseApiController;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class CategoryController extends BaseApiController
{
    public function __construct(protected CategoryService $categoryService)
    {
    }
    public function index(): JsonResponse
    {
        Gate::authorize('viewAny', Category::class);
        $categories = $this->categoryService->getAllCategories();
        return $this->sendResponse(CategoryResource::collection($categories), 'Categories retrieved successfully.');
    }

    public function store(StoreCategoryRequest $request): JsonResponse
    {
        Gate::authorize('create', Category::class);
        $data = $request->validated();
        $imageFile = $request->file('image');
        $category = $this->categoryService->createCategory($data, $imageFile);
        return $this->sendResponse(new CategoryResource($category), 'Category created successfully.', 201);
    }

    public function show(Category $category): JsonResponse
    {
        Gate::authorize('view', $category);
        $category = $this->categoryService->showCategory($category);
        return $this->sendResponse(new CategoryResource($category), 'Category retrieved successfully.');
    }

    public function update(UpdateCategoryRequest $request, Category $category): JsonResponse
    {
        Gate::authorize('update', $category);
        $data = $request->validated();
        $imageFile = $request->file('image');
        $category = $this->categoryService->updateCategory($category, $data, $imageFile);
        return $this->sendResponse(new CategoryResource($category), 'Category updated successfully.');
    }

    public function destroy(Category $category): JsonResponse
    {
        Gate::authorize('delete', $category);
        $this->categoryService->deleteCategory($category);
        return $this->sendResponse(message: 'Category deleted successfully.');
    }
}
