<?php

namespace Modules\Modules\Shared\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class BaseResourceCollection extends ResourceCollection
{
    protected string $resourceKey = 'data';
    protected string $resourceClass;
    protected bool $wrapInData = true;

    public function __construct($resource)
    {
        parent::__construct($resource);

        if (!isset($this->resourceClass)) {
            $this->resourceClass = $this->guessResourceClass();
        }
    }

    public function toArray($request)
    {
        if ($this->resource instanceof LengthAwarePaginator) {
            return $this->toPaginatedArray($request);
        }

        return $this->toNonePaginatedArray($request);
    }

    protected function toPaginatedArray($request)
    {
        $data = [
            $this->resourceKey => $this->transformCollection($this->collection),
            'meta' => $this->getPaginationMeta(),
            'links' => $this->getPaginationLinks()
        ];

        $additionalData = $this->getAdditionalData($request);

        if (!empty($additionalData)) {
            $data = array_merge($data, $additionalData);
        }

        return $data;
    }

    protected function toNonePaginatedArray($request)
    {
        $transformedCollection = $this->transformCollection($this->collection);

        if (!$this->wrapInData) {
            $transformedCollection->toArray();
        }

        $data = [
            $this->resourceKey => $transformedCollection,
        ];

        $additionalData = $this->getAdditionalData($request);

        if (!empty($additionalData)) {
            $data = array_merge($data, $additionalData);
        }

        return $data;
    }

    protected function transformCollection($collection)
    {
        if (!$this->resourceClass) {
            return $collection;
        }

        return $collection->map(function ($item) {
            return new $this->resourceClass($item);
        });
    }

    protected function getPaginationMeta()
    {
        if (!$this->resource instanceof LengthAwarePaginator) {
            return [];
        }

        return [
            'total' => $this->total(),
            'per_page' => $this->perPage(),
            'current_page' => $this->currentPage(),
            'last_page' => $this->lastPage(),
            'from' => $this->firstItem(),
            'to' => $this->lastItem(),
            'has_more_pages' => $this->hasMorePages(),
            'path' => $this->path(),
        ];
    }

    protected function getPaginationLinks(): array
    {
        if (!$this->resource instanceof LengthAwarePaginator) {
            return [];
        }

        return [
            'first' => $this->url(1),
            'last' => $this->url($this->lastPage()),
            'prev' => $this->previousPageUrl(),
            'next' => $this->nextPageUrl(),
            'current' => $this->url($this->currentPage()),
        ];
    }

    protected function getAdditionalData(Request $request): array
    {
        return [];
    }

    protected function guessResourceClass()
    {
        $collectionClass = get_class($this);
        $resourceClass = str_replace('Collection', 'Resource', $collectionClass);

        if (class_exists($resourceClass)) {
            return $resourceClass;
        }

        return null;
    }

    public function withouWrapping()
    {
        $this->wrapInData = false;
        return $this;
    }

    public function setResourceClass(string $resourceClass): static
    {
        $this->resourceClass = $resourceClass;
        return $this;
    }

}
