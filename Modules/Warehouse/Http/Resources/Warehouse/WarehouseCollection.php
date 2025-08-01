<?php

namespace Modules\Modules\Warehouse\Http\Resources\Warehouse;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Modules\Modules\Shared\Http\Resources\BaseResourceCollection;

class WarehouseCollection extends BaseResourceCollection
{
    protected string $resourceKey = 'warehouses';
    protected string $resourceClass = WarehouseResource::class;

    protected function transformCollection($collection)
    {
        return $collection->map(function ($warehouse) {
            return new WarehouseResource($warehouse);
        });
    }
}
