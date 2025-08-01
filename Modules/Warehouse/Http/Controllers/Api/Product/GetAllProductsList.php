<?php

namespace Modules\Modules\Warehouse\Http\Controllers\Api\Product;

use Illuminate\Http\Request;
use Modules\Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Modules\Shared\Services\Responder;
use Modules\Modules\Warehouse\Http\Resources\Product\ProductCollection;
use Modules\Modules\Warehouse\Models\Product;
use Modules\Warehouse\Http\Resources\Warehouse\WarehouseDocumentCollection;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     path="/api/products/all",
 *     operationId="getProductsList",
 *     tags={"Products"},
 *     summary="Get list of products",
 *     description="Returns list of products for the tenant",
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
 *                 property="data",
 *                 type="array",
 *                 @OA\Items(ref="#/components/schemas/Product")
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
class GetAllProductsList extends BaseCrudHandler
{
    /**
     * Handle the incoming request.
     */
    public function handle(array $attributes = [])
    {
        $tenantId = $this->tenant?->id;

        $query = Product::ForTenant($tenantId)->when(!empty($attributes['search']), function ($query) use ($attributes) {
            $query->where('name', 'like', '%' . $attributes['search'] . '%')
                ->orWhere('code', 'like', '%' . $attributes['search'] . '%');
        });

//        if ($include) {
//            $includes = explode(',', $include);
//            $allowedIncludes = ['racks'];
//            $validIncludes = array_intersect($includes, $allowedIncludes);
//
//            if (!empty($validIncludes)) {
//                $query->with($validIncludes);
//            }
//        }

        $products = $query->paginate(20);

        return Responder::success(['products' => new ProductCollection($products)]);
    }
}
