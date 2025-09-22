<?php

namespace Modules\Warehouse\Http\Resources\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Shared\Helpers\DateTimeHelpers;
use Modules\Warehouse\Http\Resources\ProductCategory\ProductCategoryResource;
use Modules\Warehouse\Http\Resources\WarehouseProduct\WarehouseProductCollection;
use Modules\Warehouse\Http\Resources\WarehouseProduct\WarehouseProductResource;

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
            'main_counting_unit' => $this->main_counting_unit,
            'sub_counting_unit' => $this->sub_counting_unit,
            'beginning_inventory' => $this->beginning_inventory,
            'coefficient' => $this->coefficient,
            'quantity' => $this->getTotalQuantity(),
            'product_category_id' => $this->product_category_id,
            'product_category' => [
                'id' => $this->product_category_id,
                'code' => $this->productCategory?->code,
                'name' => $this->productCategory?->name,
            ],
            'name' => $this->name,
//            'in_warehouses' => $this->when(
//                $this->warehouseProducts,
//                fn () => WarehouseProductResource::collection($this->warehouseProducts)
//            ),
            'status' => [
                'key' => $this->status,
                'value' => trans("container.{$this->status}")
            ],
            'type' => $this->getTranslatedType(),
            'in_warehouses' => WarehouseProductResource::collection($this->warehouseProducts),
            'description' => $this->description,
            'created_at' => DateTimeHelpers::gregorianDateTimeToJalali($this->created_at?->format('Y-m-d H:i:s')),
            'category' => new ProductCategoryResource($this->whenLoaded('category')),
        ];
    }
}
