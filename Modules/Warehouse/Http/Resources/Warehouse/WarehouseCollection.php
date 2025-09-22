<?php

namespace Modules\Warehouse\Http\Resources\Warehouse;

use Modules\Shared\Http\Resources\BaseResourceCollection;

class WarehouseCollection extends BaseResourceCollection
{
    protected string $resourceKey = 'warehouses';
    protected string $resourceClass = WarehouseResource::class;

    protected function transformCollection($collection)
    {
        return $collection->map(function ($warehouse) {
            return new $this->resourceClass($warehouse);
        });
    }
}
