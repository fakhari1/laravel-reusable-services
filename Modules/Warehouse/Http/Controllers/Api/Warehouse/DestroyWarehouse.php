<?php

namespace Modules\Warehouse\Http\Controllers\Api\Warehouse;

use Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Shared\Services\Responder;
use Modules\Warehouse\Models\Warehouse;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Delete(
 *     path="/api/warehouses/{id}/delete",
 *     operationId="destroyWarehouse",
 *     tags={"Warehouse > Warehouses"},
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

        if ($warehouse->racks()->count() > 0) {
            return Responder::error('انبار مورد نظر دارای رگال است و قابل حذف نیست', [], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($warehouse->documents()->count() > 0) {
            return Responder::error('انبار مورد نظر دارای سند حواله / رسید است و قابل حذف نیست', [], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($warehouse->warehouseProducts()->count() > 0) {
            return Responder::error('انبار مورد نظر دارای سند محصول است و قابل حذف نیست', [], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $warehouse->address()->delete();

        return Responder::success($warehouse->delete());
    }

    public function authorize()
    {
        return true;
    }
}
