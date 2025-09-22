<?php

namespace Modules\Warehouse\Http\Resources\ProductCategory;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductCategoryCollection extends ResourceCollection
{
    protected string $resourceKey = 'product_categories';
    protected string $resourceClass = ProductCategoryResource::class;

    protected function transformCollection($collection)
    {
        return $collection->map(function ($productCategory) {
            return new $this->resourceClass($productCategory);
        });
    }
}
