<?php

namespace Modules\Finance\Http\Resources\DetailedAccount;

use Modules\Shared\Http\Resources\BaseResourceCollection;

class DetailedAccountCollection extends BaseResourceCollection
{
    protected string $resourceKey = 'accounts';
    protected string $resourceClass = DetailedAccountResource::class;

    protected function transformCollection($collection)
    {
        return $collection->map(function ($detailedAccount) {
            return new $this->resourceClass($detailedAccount);
        });
    }
}
