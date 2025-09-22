<?php

namespace Modules\Warehouse\Http\Resources\Product;

use Modules\Shared\Http\Resources\BaseResourceCollection;

class ProductCollection extends BaseResourceCollection
{
    protected string $resourceKey = 'products';
    protected string $resourceClass = ProductResource::class;

    protected function transformCollection($collection)
    {
        return $collection->map(function ($product) {
            return new $this->resourceClass($product);
        });
    }
}
