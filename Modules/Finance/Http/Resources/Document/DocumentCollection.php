<?php

namespace Modules\Finance\Http\Resources\Document;

use Modules\Shared\Http\Resources\BaseResourceCollection;

class DocumentCollection extends BaseResourceCollection
{
    protected string $resourceKey = 'documents';
    protected string $resourceClass = DocumentResource::class;

    protected function transformCollection($collection)
    {
        return $collection->map(function ($document) {
            return new $this->resourceClass($document);
        });
    }
}
