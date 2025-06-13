<?php

namespace App\Services\Admin;

use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryService
{

  public function getAllCategories()
  {
    return Category::withCount('products')->orderBy('created_at', 'desc')->get();
  }

  public function createCategory(array $data, $imageFile = null)
  {
    if ($imageFile) {
      $data['image'] = $imageFile->store('categories', 'public');
    }
    if (isset($data['name'])) {
      $data['slug'] = Str::slug($data['name']);
    }
    return Category::create($data);
  }

  public function showCategory(Category $category)
  {
    return $category->load('products');
  }

  public function updateCategory(Category $category, array $data, $imageFile = null)
  {
    if ($imageFile) {
      if ($category->image) {
        Storage::disk('public')->delete($category->image);
      }
      $data['image'] = $imageFile->store('categories', 'public');
    }
    if (isset($data['name'])) {
      $data['slug'] = Str::slug($data['name']);
    } elseif (!isset($data['slug'])) {
      $data['slug'] = Str::slug($category->name);
    }
    $category->update($data);
    return $category;
  }

  public function deleteCategory(Category $category)
  {
    if ($category->image) {
      Storage::disk('public')->delete($category->image);
    }
    $category->delete();
  }

}