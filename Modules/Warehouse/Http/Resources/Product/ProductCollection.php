<?php

namespace Modules\Modules\Warehouse\Http\Resources\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'products' => $this->collection->map(function ($product) {
                return new ProductResource($product);
            }),
            'meta' => [
                'total' => $this->total(),
                'per_page' => $this->perPage(),
                'current_page' => $this->currentPage(),
                'last_page' => $this->lastPage(),
                'from' => $this->firstItem(),
                'to' => $this->lastItem(),
            ],
            'links' => [
                'first' => $this->url(1),
                'last' => $this->url($this->lastPage()),
                'prev' => $this->previousPageUrl(),
                'next' => $this->nextPageUrl(),
            ],
            'statistics' => [
                'total_products' => $this->total(),
                'active_products' => $this->collection->where('status', 'active')->count(),
                'inactive_products' => $this->collection->where('status', 'inactive')->count(),
                'total_stock' => $this->collection->sum('stock_count'),
                'out_of_stock_products' => $this->collection->where('stock_count', 0)->count(),
            ],
        ];
    }
}
