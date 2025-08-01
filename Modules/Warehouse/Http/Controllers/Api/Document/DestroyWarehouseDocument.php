<?php

namespace Modules\Modules\Warehouse\Http\Controllers\Api\Document;

use Modules\Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Modules\Shared\Services\Responder;
use Modules\Modules\Warehouse\Models\WarehouseDocument;
use OpenApi\Annotations as OA;

/**
 * @OA\Delete(
 *     path="/api/warehouses/documents/{id}/delete",
 *     operationId="destroyWarehouseDocument",
 *     tags={"WarehouseDocuments"},
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

        $warehouseDocument->document->delete();

        return Responder::success($warehouseDocument->delete());
    }
}
