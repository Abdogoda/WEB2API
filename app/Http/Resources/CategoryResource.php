<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'image' => asset('storage/' . $this->image),
            'products_count' => $this->when(isset($this->products_count), $this->products_count),
            'products' => ProductResource::collection(
                $this->whenLoaded('products', function () {
                    return $this->products->where('active', true)->where('stock', '>', 0);
                })
            ),
        ];
    }
}
