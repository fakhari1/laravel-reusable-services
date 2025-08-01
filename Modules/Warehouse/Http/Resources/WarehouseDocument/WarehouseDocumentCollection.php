<?php

namespace Modules\Modules\Warehouse\Http\Resources\WarehouseDocument;

use Illuminate\Http\Request;
use Modules\Modules\Shared\Http\Resources\BaseResourceCollection;

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
