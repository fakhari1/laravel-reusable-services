<?php

namespace Modules\Modules\Warehouse\Http\Resources\ProductCategory;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Modules\Warehouse\Http\Resources\Product\ProductResource;

class ProductCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
            'parent_id' => $this->parent_id,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'parent' => new ProductCategoryResource($this->whenLoaded('parent')),
            'children' => $this->when(!empty($this->children), fn() => self::collection($this->children)),
            'products' => $this->when(!empty($this->products), fn() => ProductResource::collection($this->products)),
            'children_count' => $this->when(
                !empty($this->children), fn() => $this->children->count()
            ),
            'products_count' => $this->when(
                !empty($this->products), fn() => $this->products->count()
            ),
        ];
    }
}
