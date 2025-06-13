<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    return [
      'name' => 'required|string|max:255|unique:categories,name',
      'image' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048',
      'description' => 'nullable|string',
    ];
  }
}
