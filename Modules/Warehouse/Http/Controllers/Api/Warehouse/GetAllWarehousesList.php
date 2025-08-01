<?php

namespace Modules\Modules\Warehouse\Http\Controllers\Api\Warehouse;

use Modules\Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Modules\Shared\Services\Responder;
use Modules\Modules\Warehouse\Http\Resources\Warehouse\WarehouseCollection;
use Modules\Modules\Warehouse\Models\Warehouse;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     path="/api/warehouses/all",
 *     operationId="getWarehousesList",
 *     tags={"Warehouses"},
 *     summary="Get list of warehouses",
 *     description="Returns list of warehouses for the tenant",
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
 *                 @OA\Items(ref="#/components/schemas/Warehouse")
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
class GetAllWarehousesList extends BaseCrudHandler
{
    /**
     * Handle the incoming request.
     */
    public function handle(array $attributes = [])
    {
        $tenantId = $this->tenant?->id;

        $query = Warehouse::forTenant($tenantId)->with(['racks', 'address', 'storekeeper']);

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

            $warehouses = $query->paginate(
                $paginationParams['per_page'],
                ['*'],
                'page',
                $paginationParams['page']
            );
        } else {
            $warehouses = $query->get();
        }


        return Responder::success(new WarehouseCollection($warehouses));
    }
}
