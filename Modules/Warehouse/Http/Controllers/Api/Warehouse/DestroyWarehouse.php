<?php

namespace Modules\Modules\Warehouse\Http\Controllers\Api\Warehouse;

use Modules\Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Modules\Shared\Services\Responder;
use Modules\Modules\Warehouse\Models\Warehouse;
use OpenApi\Annotations as OA;

/**
 * @OA\Delete(
 *     path="/api/warehouses/{id}/delete",
 *     operationId="destroyWarehouse",
 *     tags={"Warehouses"},
 *     summary="Delete existing warehouse with it's racks",
 *     description="Deletes a record and returns no content",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer"),
 *         description="Warehouse ID"
 *     ),
 *     @OA\Response(
 *         response=204,
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
class DestroyWarehouse extends BaseCrudHandler
{
    /**
     * Handle the incoming request.
     */
    public function execute(array $attributes = [])
    {
        $warehouse = Warehouse::whereId($attributes['id'])->firstOrFail();

        $warehouse->racks()->each(function ($rack) {
            $rack->delete();
        });

        $warehouse->address()->delete();

        return Responder::success($warehouse->delete());
    }
}
