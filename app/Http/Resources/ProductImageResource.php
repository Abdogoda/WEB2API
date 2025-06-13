<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductImageResource extends JsonResource
{
  public function toArray($request): array
  {
    return [
      'id' => $this->id,
      'product_id' => $this->product_id,
      'path' => asset('storage/' . $this->path),
      'is_primary' => $this->is_primary,
    ];
  }
}
