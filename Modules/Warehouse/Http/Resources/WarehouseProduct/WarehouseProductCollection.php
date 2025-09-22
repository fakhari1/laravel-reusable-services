<?php

namespace Modules\Warehouse\Http\Resources\WarehouseProduct;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Modules\Shared\Http\Resources\BaseResourceCollection;
use Modules\Warehouse\Http\Resources\Product\ProductResource;

class WarehouseProductCollection extends BaseResourceCollection
{
    protected string $resourceKey = 'products';
    protected string $resourceClass = WarehouseProductResource::class;

    protected function transformCollection($collection)
    {
        return $collection->map(function ($warehouseProduct) {
            return new $this->resourceClass($warehouseProduct);
        });
    }
}
