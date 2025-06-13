<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class ListProductRequest extends FormRequest
{
  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    return [
      'search' => 'sometimes|string',
      'category_ids' => 'sometimes|array',
      'category_ids.*' => 'integer|exists:categories,id',
      'min_price' => 'sometimes|numeric|min:0',
      'max_price' => 'sometimes|numeric|min:0',
      'featured' => 'sometimes|boolean',
      'per_page' => 'sometimes|integer|min:1|max:100',
    ];
  }

  public function withValidator($validator)
  {
    $validator->after(function ($validator) {
      $min = $this->input('min_price');
      $max = $this->input('max_price');
      if (!is_null($min) && !is_null($max) && $min > $max) {
        $validator->errors()->add('min_price', 'min_price cannot be greater than max_price.');
      }
    });
  }
}
