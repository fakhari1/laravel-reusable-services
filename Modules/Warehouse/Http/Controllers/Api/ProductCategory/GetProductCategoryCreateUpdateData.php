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
 *     path="/api/product-categories/get-create-update-data",
 *     operationId="getProductCategoryCreateData",
 *     tags={"Warehouse > ProductCategories"},
 *     summary="Get product category create data",
 *     description="Returns product category create data data",
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
class GetProductCategoryCreateUpdateData extends BaseCrudHandler
{
    /**
     * Handle the incoming request.
     */
    public function execute(array $attributes = [])
    {

        $productCategories = ProductCategory::ForTenant($this->tenant->id)->select('id', 'name');

        if (isset($attributes['will_update'])) {
            $productCategories->where('id', '!=', $attributes['will_update'])
                ->where(function ($query) use ($attributes) {
                    $query->where('parent_id', '!=', $attributes['will_update'])
                        ->orWhereNull('parent_id');
                });
        }

        $productCategories = $productCategories->orderBy('parent_id')->get();

        $statuses = ProductCategory::getTranslatedStatuses();

        return Responder::success([
            'code' => ProductCategory::ForTenant($this->tenant?->id)->count() + 1,
            'product_categories' => $productCategories,
            'statuses' => $statuses
        ]);
    }

    public function authorize()
    {
        return true;
    }
}
