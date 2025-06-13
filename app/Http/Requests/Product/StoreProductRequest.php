<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:products,name',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|gt:0',
            'stock' => 'required|numeric|gt:0',
            'active' => 'nullable|in:on,off',
            'featured' => 'nullable|in:on,off',
            'images.*' => 'required|image|mimes:jpg,png,jpeg,gif|max:2048',
        ];
    }
}
