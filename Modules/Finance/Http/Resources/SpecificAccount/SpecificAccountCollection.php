<?php

namespace Modules\Finance\Http\Resources\SpecificAccount;

use Modules\Shared\Http\Resources\BaseResourceCollection;

class SpecificAccountCollection extends BaseResourceCollection
{
    protected string $resourceKey = 'accounts';
    protected string $resourceClass = SpecificAccountResource::class;

    protected function transformCollection($collection)
    {
        return $collection->map(function ($specificAccount) {
            return new $this->resourceClass($specificAccount);
        });
    }
}
