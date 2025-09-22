<?php

namespace Modules\Warehouse\Http\Controllers\Api\ProductCategory;

use Modules\Shared\Http\Controllers\BaseCrudHandler;
use Illuminate\Http\Request;
use Modules\Shared\Services\Responder;
use Modules\Warehouse\Models\Product;
use Modules\Warehouse\Models\ProductCategory;
use OpenApi\Annotations as OA;

/**
 * @OA\Delete(
 *     path="/api/product-categories/{id}/delete",
 *     operationId="deleteProductCategory",
 *     tags={"Warehouse > ProductCategories"},
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

    public function authorize()
    {
        return true;
    }
}
