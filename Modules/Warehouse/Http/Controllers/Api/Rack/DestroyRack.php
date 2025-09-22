<?php

namespace Modules\Warehouse\Http\Controllers\Api\Rack;

use Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Shared\Services\Responder;
use Modules\Warehouse\Models\WarehouseRack;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Delete(
 *     path="/api/warehouses/racks/{id}/delete",
 *     operationId="destoryRack",
 *     tags={"Warehouse > Racks"},
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
        $rack = WarehouseRack::findOrFail($attributes['id']);

        if ($rack->warehouseProducts()->count() > 0) {
            return Responder::error('رگال مورد نظر دارای محصول است و قابل حذف نیست', [], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($rack->documentProducts()->count() > 0) {
            return Responder::error('رگال مورد نظر دارای سند است و قابل حذف نیست', [], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return Responder::success($rack->delete());
    }

    public function authorize()
    {
        return true;
    }
}
