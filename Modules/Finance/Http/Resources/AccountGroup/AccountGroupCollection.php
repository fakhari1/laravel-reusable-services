<?php

namespace Modules\Finance\Http\Resources\AccountGroup;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Modules\Shared\Http\Resources\BaseResourceCollection;

class AccountGroupCollection extends BaseResourceCollection
{
    protected string $resourceKey = 'account_groups';
    protected string $resourceClass = AccountGroupResource::class;

    protected function transformCollection($collection)
    {
        return $collection->map(function ($accountGroup) {
            return new $this->resourceClass($accountGroup);
        });
    }
}
