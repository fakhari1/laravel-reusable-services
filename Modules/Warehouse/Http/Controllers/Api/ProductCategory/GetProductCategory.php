<?php

namespace Modules\Warehouse\Http\Controllers\Api\ProductCategory;

use Modules\Shared\Http\Controllers\BaseCrudHandler;
use Illuminate\Http\Request;
use Modules\Shared\Services\Responder;
use Modules\Warehouse\Http\Resources\ProductCategory\ProductCategoryResource;
use Modules\Warehouse\Models\ProductCategory;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     path="/api/product-categories/{id}/get",
 *     operationId="getProductCategoryById",
 *     tags={"Warehouse > ProductCategories"},
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
 *         description="Successful operation"
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

    public function authorize()
    {
        return true;
    }
}
