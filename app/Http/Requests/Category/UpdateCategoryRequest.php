<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
{
  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    $categoryId = $this->route('category')?->id ?? null;
    return [
      'name' => 'sometimes|string|max:255|unique:categories,name,' . $this->category->id,
      'image' => 'sometimes|image|mimes:jpg,png,jpeg,gif|max:2048',
      'description' => 'nullable|string',
    ];
  }
}
