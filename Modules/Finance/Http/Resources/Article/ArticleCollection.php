<?php

namespace Modules\Finance\Http\Resources\Article;

use Modules\Shared\Http\Resources\BaseResourceCollection;

class ArticleCollection extends BaseResourceCollection
{
    protected string $resourceKey = 'articles';
    protected string $resourceClass = ArticleResource::class;

    protected function transformCollection($collection)
    {
        return $collection->map(function ($article) {
            return new $this->resourceClass($article);
        });
    }
}
