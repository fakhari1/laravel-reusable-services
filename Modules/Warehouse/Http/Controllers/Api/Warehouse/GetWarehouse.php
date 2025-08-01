<?php

namespace Modules\Modules\Warehouse\Http\Controllers\Api\Warehouse;

use Modules\Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Modules\Shared\Services\Responder;
use Modules\Modules\Warehouse\Http\Resources\Warehouse\WarehouseResource;
use Modules\Modules\Warehouse\Models\Warehouse;
use Modules\Warehouse\Http\Resources\Warehouse\WarehouseDocumentResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     path="/api/warehouses/{id}/get",
 *     operationId="getWarehouseById",
 *     tags={"Warehouses"},
 *     summary="Get warehouse information",
 *     description="Returns warehouse data",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer"),
 *         description="Warehouse ID"
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(ref="#/components/schemas/Warehouse")
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="Forbidden"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Warehouse not found"
 *     )
 * )
 */
class GetWarehouse extends BaseCrudHandler
{
    /**
     * Handle the incoming request.
     */
    public function handle(array $attributes = [])
    {
//        $include = $request->get('include', '');
//
//        if ($include) {
//            $includes = explode(',', $include);
//            $allowedIncludes = ['racks'];
//            $validIncludes = array_intersect($includes, $allowedIncludes);
//
//            if (!empty($validIncludes)) {
//                $this->model->load($validIncludes);
//            }
//        }

        $warehouse = Warehouse::findOrFail($attributes['id'])->load(['racks', 'address', 'storekeeper']);

        return Responder::success(
            new WarehouseResource($warehouse)
        );
    }
}
