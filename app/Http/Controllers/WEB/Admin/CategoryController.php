<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Support\Facades\Gate;

class CategoryController extends Controller
{
    public function __construct(protected CategoryService $categoryService)
    {
    }

    public function index()
    {
        Gate::authorize('viewAny', Category::class);
        $categories = $this->categoryService->getAllCategories();
        return view('admin.categories.index', compact('categories'));
    }

    public function store(StoreCategoryRequest $request)
    {
        Gate::authorize('create', Category::class);
        $data = $request->validated();
        $imageFile = $request->file('image');
        $this->categoryService->createCategory($data, $imageFile);
        return back()->with('success', 'Category created successfully.');
    }

    public function show(Category $category)
    {
        Gate::authorize('view', $category);
        $category = $this->categoryService->showCategory($category);
        return view('admin.categories.show', compact('category'));
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        Gate::authorize('update', $category);
        $data = $request->validated();
        $imageFile = $request->file('image');
        $this->categoryService->updateCategory($category, $data, $imageFile);
        return back()->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        Gate::authorize('delete', $category);
        $this->categoryService->deleteCategory($category);
        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
    }
}
