<?php

namespace Modules\Modules\Warehouse\Http\Controllers\Api\Rack;

use Modules\Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Modules\Shared\Services\Responder;
use Modules\Modules\Warehouse\Models\Rack;
use OpenApi\Annotations as OA;

/**
 * @OA\Delete(
 *     path="/api/warehouses/racks/{id}/delete",
 *     operationId="destoryRack",
 *     tags={"Racks"},
 *     summary="Delete existing rack",
 *     description="Deletes a record and returns no content",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer"),
 *         description="Rack ID"
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

class DestroyRack extends BaseCrudHandler
{
    /**
     * Handle the incoming request.
     */
    public function execute(array $attributes = [])
    {
        return Responder::success(Rack::findOrFail($attributes['id'])->delete());
    }
}
