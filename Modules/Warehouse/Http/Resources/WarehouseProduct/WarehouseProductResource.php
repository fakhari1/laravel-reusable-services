<?php

namespace Modules\Warehouse\Http\Resources\WarehouseProduct;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WarehouseProductResource extends JsonResource
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
            'code' => $this->product?->code,
            'product_category_id' => $this->product?->product_category_id,
            'product_category' => [
                'id' => $this->product?->product_category_id,
                'code' => $this->product?->productCategory?->code,
                'name' => $this->product?->productCategory?->name,
            ],
            'name' => $this->product?->name,
            'warehouse' => [
                'id' => $this->warehouse?->id,
                'name' => $this->warehouse?->name
            ],
            'rack' => [
                'id' => $this->rack?->id,
                'name' => $this->rack?->name,
            ],
            'main_counting_unit' => $this->product?->main_counting_unit,
            'sub_counting_unit' => $this->product?->sub_counting_unit,
            'beginning_inventory' => $this->product?->beginning_inventory,
            'quantity' => $this->quantity,
            'coefficient' => $this->product?->coefficient,
            'status' => [
                'key' => $this->product?->status,
                'value' => trans("container.{$this->product?->status}")
            ],
            'type' => $this->product?->getTranslatedType(),
            'description' => $this->product?->description,
        ];
    }
}
