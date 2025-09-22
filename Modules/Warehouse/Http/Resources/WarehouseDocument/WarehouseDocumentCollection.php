<?php

namespace Modules\Warehouse\Http\Resources\WarehouseDocument;

use Illuminate\Http\Request;
use Modules\Shared\Http\Resources\BaseResourceCollection;
use Modules\Warehouse\Http\Resources\Warehouse\WarehouseResource;

class WarehouseDocumentCollection extends BaseResourceCollection
{
    protected string $resourceKey = 'warehouse_documents';
    protected string $resourceClass = WarehouseDocumentResource::class;

    protected function transformCollection($collection)
    {
        return $collection->map(function ($warehouse) {
            return new $this->resourceClass($warehouse);
        });
    }
}
