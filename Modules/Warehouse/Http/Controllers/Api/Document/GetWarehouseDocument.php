<?php

namespace Modules\Modules\Warehouse\Http\Controllers\Api\Document;

use Modules\Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Modules\Shared\Services\Responder;
use Modules\Modules\Warehouse\Http\Resources\WarehouseDocument\WarehouseDocumentResource;
use Modules\Modules\Warehouse\Models\WarehouseDocument;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     path="/api/warehouses/documents/{id}/get",
 *     operationId="getWarehouseDocumentById",
 *     tags={"WarehouseDocuments"},
 *     summary="Get warehouse document information",
 *     description="Returns warehouse document data",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer"),
 *         description="Warehouse Document ID"
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
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
class GetWarehouseDocument extends BaseCrudHandler
{
    public function execute(array $attributes = [])
    {
        $warehouseDocument = WarehouseDocument::findOrFail($attributes['id'])->load(['document', 'staff', 'warehouse']);

        return Responder::success(new WarehouseDocumentResource($warehouseDocument));

    }
}
