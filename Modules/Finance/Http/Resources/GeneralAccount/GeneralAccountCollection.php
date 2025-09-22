<?php

namespace Modules\Finance\Http\Resources\GeneralAccount;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Modules\Shared\Http\Resources\BaseResourceCollection;

class GeneralAccountCollection extends BaseResourceCollection
{
    protected string $resourceKey = 'accounts';
    protected string $resourceClass = GeneralAccountResource::class;

    protected function transformCollection($collection)
    {
        return $collection->map(function ($generalAccount) {
            return new $this->resourceClass($generalAccount);
        });
    }
}
