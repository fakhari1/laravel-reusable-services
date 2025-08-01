<?php

namespace Modules\Modules\Warehouse\Http\Controllers\Api\ProductCategory;

use Illuminate\Http\Request;
use Modules\Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Modules\Shared\Services\Responder;
use Modules\Modules\Warehouse\Models\ProductCategory;
use OpenApi\Annotations as OA;

/**
 * @OA\Delete(
 *     path="/api/product-categories/{id}/delete",
 *     operationId="deleteProductCategory",
 *     tags={"ProductCategories"},
 *     summary="Delete existing product category",
 *     description="Deletes a record and returns no content",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer"),
 *         description="ProductCategory ID"
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
class DestroyProductCategory extends BaseCrudHandler
{
    public function execute(array $attributes = [])
    {
        return Responder::success(ProductCategory::findOrFail($attributes['id'])->delete());
    }
}
