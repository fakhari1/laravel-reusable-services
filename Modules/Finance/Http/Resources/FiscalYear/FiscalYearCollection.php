<?php

namespace Modules\Finance\Http\Resources\FiscalYear;

use Modules\Shared\Http\Resources\BaseResourceCollection;

class FiscalYearCollection extends BaseResourceCollection
{
    protected string $resourceKey = 'fiscal_years';
    protected string $resourceClass = FiscalYearResource::class;

    protected function transformCollection($collection)
    {
        return $collection->map(function ($fiscalYear) {
            return new $this->resourceClass($fiscalYear);
        });
    }
}
