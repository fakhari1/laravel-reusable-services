<?php

namespace Modules\Modules\Warehouse\Http\Controllers\Api\Product;

use Illuminate\Http\Request;
use Modules\Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Modules\Shared\Services\Responder;
use Modules\Modules\Warehouse\Http\Resources\Product\ProductResource;
use Modules\Modules\Warehouse\Models\Product;
use Modules\Warehouse\Http\Resources\Warehouse\WarehouseDocumentResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     path="/api/products/{id}/get",
 *     operationId="getProductById",
 *     tags={"Products"},
 *     summary="Get product information",
 *     description="Returns product data",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer"),
 *         description="Product ID"
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(ref="#/components/schemas/Product")
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="Forbidden"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Product not found"
 *     )
 * )
 */
class GetProduct extends BaseCrudHandler
{
    /**
     * Handle the incoming request.
     */
    public function handle(array $attributes = [])
    {
        $product = Product::findOrFail($attributes['id']);

        return Responder::success([
            'product' => new ProductResource($product)
        ]);
    }
}
