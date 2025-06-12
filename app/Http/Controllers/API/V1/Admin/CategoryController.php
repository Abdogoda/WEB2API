<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\API\BaseApiController;
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
        return $this->sendResponse($categories, 'Categories retrieved successfully.');
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048',
            'description' => 'nullable|string',
        ]);

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

        return $this->sendResponse($category, 'Category created successfully.', 201);
    }

    public function show(Category $category): JsonResponse
    {
        $category->load('products');
        return $this->sendResponse($category, 'Category retrieved successfully.');
    }

    public function update(Request $request, Category $category): JsonResponse
    {
        $data = $request->validate([
            'name' => 'sometimes|string|max:255|unique:categories,name,' . $category->id,
            'image' => 'sometimes|image|mimes:jpg,png,jpeg,gif|max:2048',
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $imagePath = $request->file('image')->store('categories', 'public');
            $category->image = $imagePath;
        }

        $data['slug'] = Str::slug($request->name ?? $category->name);

        $category->update($data);

        return $this->sendResponse($category, 'Category updated successfully.');
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
