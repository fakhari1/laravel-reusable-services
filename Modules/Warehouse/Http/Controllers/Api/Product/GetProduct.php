<?php

namespace Modules\Warehouse\Http\Controllers\Api\Product;

use Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Shared\Services\Responder;
use Modules\Warehouse\Http\Resources\Warehouse\WarehouseDocumentResource;
use Modules\Warehouse\Http\Resources\WarehouseProduct\WarehouseProductResource;
use Modules\Warehouse\Models\Product;
use Modules\Warehouse\Models\WarehouseProduct;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     path="/api/products/{id}/get",
 *     operationId="getProductById",
 *     tags={"Warehouse > Products"},
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
 *         description="Successful operation"
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
        $product = WarehouseProduct::where('product_id', $attributes['id'])->first();

        return Responder::success([
            'product' => new WarehouseProductResource($product)
        ]);
    }

    public function authorize()
    {
        return true;
    }
}
