<?php

namespace Modules\Modules\Warehouse\Http\Resources\WarehouseDocument;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Modules\Warehouse\Http\Resources\Product\ProductResource;

class WarehouseDocumentResource extends JsonResource
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
            'warehouse_id' => $this->warehouse_id,
            'type' => $this->type,
            'document' => $this->document,
            'status' => $this->status,
            'description' => $this->description,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'products' => $this->when(
                $this->products,
                fn() => ProductResource::collection($this->products)
            ),
            'products_count' => $this->when(
                $this->products,
                fn() => $this->products->count()
            ),
        ];
    }
}
