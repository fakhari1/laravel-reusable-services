<?php

namespace Modules\Warehouse\Http\Controllers\Api\Warehouse;

use Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Shared\Services\Responder;
use Modules\Warehouse\Http\Resources\Warehouse\WarehouseDocumentResource;
use Modules\Warehouse\Http\Resources\Warehouse\WarehouseResource;
use Modules\Warehouse\Models\Warehouse;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     path="/api/warehouses/{id}/get",
 *     operationId="getWarehouseById",
 *     tags={"Warehouse > Warehouses"},
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
 *         description="Successful operation"
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
        $warehouse = Warehouse::findOrFail($attributes['id'])->load(['racks', 'address', 'storekeeper']);

        return Responder::success(
            new WarehouseResource($warehouse)
        );
    }

    public function authorize()
    {
        return true;
    }
}
