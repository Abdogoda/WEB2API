<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    return [
      'name' => 'sometimes|string|max:255|unique:products,name,' . $this->product->id,
      'description' => 'nullable|string',
      'category_id' => 'sometimes|exists:categories,id',
      'price' => 'sometimes|numeric|gt:0',
      'stock' => 'sometimes|numeric|gt:0',
      'active' => 'nullable|in:on,off',
      'featured' => 'nullable|in:on,off',
      'images.*' => 'sometimes|image|mimes:jpg,png,jpeg,gif|max:2048',
    ];
  }
}
