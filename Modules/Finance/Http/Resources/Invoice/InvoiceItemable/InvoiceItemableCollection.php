<?php

namespace Modules\Finance\Http\Resources\Invoice\InvoiceItemable;

use Modules\Shared\Http\Resources\BaseResourceCollection;

class InvoiceItemableCollection extends BaseResourceCollection
{
    protected string $resourceKey = 'invoice_itemables';
    protected string $resourceClass = InvoiceItemableResource::class;

    protected function transformCollection($collection)
    {
        return $collection->map(function ($itemable) {
            return new $this->resourceClass($itemable);
        });
    }
}
