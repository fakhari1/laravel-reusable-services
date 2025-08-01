<?php

namespace Modules\Modules\Warehouse\Http\Controllers\Api\Product;

use Modules\Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Modules\Shared\Services\Responder;
use Modules\Modules\Warehouse\Models\Product;
use OpenApi\Annotations as OA;

/**
 * @OA\Delete(
 *     path="/api/products/{id}/delete",
 *     operationId="deleteProduct",
 *     tags={"Products"},
 *     summary="Delete existing product",
 *     description="Deletes a record and returns no content",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer"),
 *         description="Product ID"
 *     ),
 *     @OA\Response(
 *         response=204,
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
class DestroyProduct extends BaseCrudHandler
{
    /**
     * Handle the incoming request.
     */
    public function execute(array $attributes = [])
    {
        return Responder::success(Product::findOrFail($attributes['id'])->delete());
    }
}
