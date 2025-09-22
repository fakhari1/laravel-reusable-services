<?php

namespace Modules\Warehouse\Http\Resources\Rack;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Shared\Helpers\DateTimeHelpers;

class RackResource extends JsonResource
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
            'warehouse' => [
                'id' => $this->warehouse_id,
                'name' => $this->warehouse?->name
            ],
            'name' => $this->name,
            'description' => $this->description,
//            'code' => $this->code,
            'created_at' => DateTimeHelpers::gregorianDateTimeToJalali($this->created_at?->format('Y-m-d H:i:s')),
//            'warehouse' => $this->when(!empty($this->warehouse), fn() => new WarehouseResource($this->warehouse)),
        ];
    }
}
