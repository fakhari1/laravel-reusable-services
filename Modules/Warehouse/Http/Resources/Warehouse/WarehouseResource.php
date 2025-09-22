<?php

namespace Modules\Warehouse\Http\Resources\Warehouse;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Shared\Helpers\DateTimeHelpers;
use Modules\Warehouse\Http\Resources\Rack\RackResource;
use Modules\Warehouse\Http\Resources\WarehouseProduct\WarehouseProductResource;

class WarehouseResource extends JsonResource
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
            'name' => $this->name,
            'code' => $this->code,
            'storekeeper_id' => $this->storekeeper_id,
            'storekeeper' => [
                'key' => $this->storekeeper_id,
                'value' => $this->storekeeper_label
            ],
            'address' => $this->when($this->address, fn() => $this->address->text),
            'account_id' => $this->account_id,
            'account' => [
                'id' => 1,
                'code' => '1102',
                'title' => 'حساب اصلی'
            ],
            'description' => $this->description,
            'created_at' => DateTimeHelpers::gregorianDateTimeToJalali($this->created_at?->format('Y-m-d H:i:s')),
            'racks' => $this->when(
                $this->racks,
                fn() => RackResource::collection($this->racks)
            ),
            'racks_count' => $this->when(
                $this->racks,
                fn() => $this->racks->count()
            ),
            'products' => $this->when(
                $this->warehouseProducts,
                fn() => WarehouseProductResource::collection($this->warehouseProducts)
            ),
            'products_count' => $this->when(
                $this->warehouseProducts,
                fn() => $this->warehouseProducts->count()
            )
        ];
    }
}
