<?php

namespace Modules\Modules\Warehouse\Http\Resources\Warehouse;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Modules\Warehouse\Http\Resources\Rack\RackResource;

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
            'type' => $this->type,
            'storekeeper_id' => $this->storekeeper_id,
            'address' => $this->when($this->address, fn() => $this->address->text),
            'account_id' => $this->account_id,
            'description' => $this->description,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'racks' => $this->when(
                $this->racks,
                fn() => RackResource::collection($this->racks)
            ),
            'racks_count' => $this->when(
                $this->racks,
                fn() => $this->racks->count()
            ),
        ];
    }
}
