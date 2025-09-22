<?php

namespace Modules\Warehouse\Http\Resources\Rack;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Modules\Warehouse\Http\Resources\WarehouseProduct\WarehouseProductResource;

class RackCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    protected string $resourceKey = 'racks';
    protected string $resourceClass = RackResource::class;

    protected function transformCollection($collection)
    {
        return $collection->map(function ($rack) {
            return new $this->resourceClass($rack);
        });
    }
}
