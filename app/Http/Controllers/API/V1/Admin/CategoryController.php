<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\API\BaseApiController;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends BaseApiController
{
    public function index(): JsonResponse
    {
        $categories = Category::withCount('products')->orderBy('created_at', 'desc')->get();
        return $this->sendResponse(CategoryResource::collection($categories), 'Categories retrieved successfully.');
    }

    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('categories', 'public');
        }

        $category = Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'image' => $imagePath,
            'description' => $request->description,
        ]);

        return $this->sendResponse(new CategoryResource($category), 'Category created successfully.', 201);
    }

    public function show(Category $category): JsonResponse
    {
        $category->load('products');
        return $this->sendResponse(new CategoryResource($category), 'Category retrieved successfully.');
    }

    public function update(UpdateCategoryRequest $request, Category $category): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $imagePath = $request->file('image')->store('categories', 'public');
            $category->image = $imagePath;
        }

        $data['slug'] = Str::slug($request->name ?? $category->name);

        $category->update($data);

        return $this->sendResponse(new CategoryResource($category), 'Category updated successfully.');
    }

    public function destroy(Category $category): JsonResponse
    {
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();
        return $this->sendResponse(message: 'Category deleted successfully.');
    }
}
