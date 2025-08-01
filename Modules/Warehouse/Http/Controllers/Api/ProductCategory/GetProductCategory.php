<?php

namespace Modules\Modules\Warehouse\Http\Controllers\Api\ProductCategory;

use Illuminate\Http\Request;
use Modules\Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Modules\Shared\Services\Responder;
use Modules\Modules\Warehouse\Http\Resources\ProductCategory\ProductCategoryResource;
use Modules\Modules\Warehouse\Models\ProductCategory;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     path="/api/product-categories/{id}/get",
 *     operationId="getProductCategoryById",
 *     tags={"ProductCategories"},
 *     summary="Get product category information",
 *     description="Returns product category data",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer"),
 *         description="ProductCategory ID"
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(ref="#/components/schemas/ProductCategory")
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="Forbidden"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Product category not found"
 *     )
 * )
 */
class GetProductCategory extends BaseCrudHandler
{
    public function execute(array $attributes = [])
    {
        $productCategory = ProductCategory::findOrFail($attributes['id']);

        return Responder::success([
            'data' => new ProductCategoryResource($productCategory)
        ]);
    }
}
