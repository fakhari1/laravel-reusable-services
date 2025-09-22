<?php

namespace Modules\Warehouse\Http\Controllers\Api\ProductCategory;

use Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Shared\Services\Responder;
use Modules\Warehouse\Http\Resources\ProductCategory\ProductCategoryCollection;
use Modules\Warehouse\Models\ProductCategory;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     path="/api/product-categories/all",
 *     operationId="getProductCategoriesList",
 *     tags={"Warehouse > ProductCategories"},
 *     summary="Get list of product categories",
 *     description="Returns list of product categories for the tenant",
 *     @OA\Parameter(
 *         name="page",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="integer"),
 *         description="Page number"
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="data"
 *             ),
 *             @OA\Property(
 *                 property="meta",
 *                 type="object",
 *                 @OA\Property(property="total", type="integer"),
 *                 @OA\Property(property="per_page", type="integer"),
 *                 @OA\Property(property="current_page", type="integer"),
 *                 @OA\Property(property="last_page", type="integer"),
 *                 @OA\Property(property="from", type="integer"),
 *                 @OA\Property(property="to", type="integer")
 *             ),
 *             @OA\Property(
 *                 property="links",
 *                 type="object",
 *                 @OA\Property(property="first", type="string"),
 *                 @OA\Property(property="last", type="string"),
 *                 @OA\Property(property="prev", type="string"),
 *                 @OA\Property(property="next", type="string")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized"
 *     )
 * )
 */
class GetAllProductCategoriesList extends BaseCrudHandler
{
    /**
     * Handle the incoming request.
     */
    public function handle(array $attributes = [])
    {
        $tenantId = $this->tenant?->id;

        $query = ProductCategory::ForTenant($tenantId);

//        if ($include) {
//            $includes = explode(',', $include);
//            $allowedIncludes = ['racks'];
//            $validIncludes = array_intersect($includes, $allowedIncludes);
//
//            if (!empty($validIncludes)) {
//                $query->with($validIncludes);
//            }
//        }

        $productCategories = $query->orderBy('created_at', 'desc')->paginate(200);

        return Responder::success(new ProductCategoryCollection($productCategories));
    }

    public function authorize()
    {
        return true;
    }
}
