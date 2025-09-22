<?php

namespace Modules\Warehouse\Http\Controllers\Api\Document;

use Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Shared\Services\Responder;
use Modules\Warehouse\Models\WarehouseDocument;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Delete(
 *     path="/api/warehouses/documents/{id}/delete",
 *     operationId="destroyWarehouseDocument",
 *     tags={"Warehouse > Documents"},
 *     summary="Delete existing warehouse document",
 *     description="Deletes a record and returns no content",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer"),
 *         description="Warehouse Document ID"
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
 *         description="Warehouse document not found"
 *     )
 * )
 */
class DestroyWarehouseDocument extends BaseCrudHandler
{
    public function execute(array $attributes = [])
    {
        $warehouseDocument = WarehouseDocument::whereId($attributes['id'])->firstOrFail();
        if ($warehouseDocument->products()->count()) {
            return Responder::error('سند مورد نظر دارای محصول است ابتدا آن ها را حذف کنید', null, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $warehouseDocument->documentable->delete();

        return Responder::success($warehouseDocument->delete());
    }

    public function authorize()
    {
        return true;
    }
}
