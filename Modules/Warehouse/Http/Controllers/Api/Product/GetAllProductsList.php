<?php

namespace Modules\Warehouse\Http\Controllers\Api\Product;

use Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Shared\Services\Responder;
use Modules\Warehouse\Http\Resources\Product\ProductCollection;
use Modules\Warehouse\Http\Resources\Warehouse\WarehouseDocumentCollection;
use Modules\Warehouse\Http\Resources\WarehouseProduct\WarehouseProductCollection;
use Modules\Warehouse\Models\Product;
use Modules\Warehouse\Models\WarehouseProduct;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     path="/api/products/all",
 *     operationId="getProductsList",
 *     tags={"Warehouse > Products"},
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
class GetAllProductsList extends BaseCrudHandler
{
    /**
     * Handle the incoming request.
     */
    public function handle(array $attributes = [])
    {
        $tenantId = $this->tenant?->id;

        $query = Product::ForTenant($tenantId);

        if ($this->request->has('search')) {
            $search = $this->request->get('search');

            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('code', 'like', '%' . $search . '%');
            });
        }


//        if ($include) {
//            $includes = explode(',', $include);
//            $allowedIncludes = ['racks'];
//            $validIncludes = array_intersect($includes, $allowedIncludes);
//
//            if (!empty($validIncludes)) {
//                $query->with($validIncludes);
//            }
//        }

        if ($this->shouldPaginate()) {
            $paginationParams = $this->getPaginationParams();

            $products = $query->paginate(
                $paginationParams['per_page'],
                ['*'],
                'page',
                $paginationParams['page']
            );
        } else {
            $products = $query->orderBy('created_at', 'desc')->get();
        }

        return Responder::success(new ProductCollection($products));
    }

    public function authorize()
    {
        return true;
    }
}
