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
 *     path="/api/product-categories/get-create-update-data",
 *     operationId="getProductCategoryCreateData",
 *     tags={"ProductCategories"},
 *     summary="Get product category create data",
 *     description="Returns product category create data data",
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
 *         description="Product not found"
 *     )
 * )
 */
class GetProductCategoryCreateUpateData extends BaseCrudHandler
{
    /**
     * Handle the incoming request.
     */
    public function execute(array $attributes = [])
    {
        $productCategories = ProductCategory::where('parent_id', null)->get();
        $statuses = ProductCategory::getTranslatedStatuses();

        return Responder::success([
            'product_categories' => ProductCategoryResource::collection($productCategories),
            'statuses' => $statuses
        ]);
    }
}
