<?php

namespace Modules\Modules\Warehouse\Http\Resources\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Modules\Warehouse\Http\Resources\ProductCategory\ProductCategoryResource;

class ProductResource extends JsonResource
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
            'product_category_id' => $this->product_category_id,
            'name' => $this->name,
            'main_counting_unit' => $this->main_counting_unit,
            'stock_count' => $this->stock_count,
            'coefficient' => $this->coefficient,
            'sub_counting_unit' => $this->sub_counting_unit,
            'status' => $this->status,
            'type' => $this->type,
            'description' => $this->description,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'category' => new ProductCategoryResource($this->whenLoaded('category')),
            'stock_status' => $this->when(
                $this->stock_count !== null,
                fn() => $this->stock_count > 0 ? 'in_stock' : 'out_of_stock'
            ),
            'secondary_stock_count' => $this->when(
                $this->ratio && $this->sub_counting_unit,
                fn() => round($this->stock_count / $this->ratio, 2)
            ),
        ];
    }
}
