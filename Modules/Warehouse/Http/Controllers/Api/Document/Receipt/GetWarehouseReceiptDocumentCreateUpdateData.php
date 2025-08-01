<?php

namespace Modules\Modules\Warehouse\Http\Controllers\Api\Document\Receipt;

use Modules\Modules\Shared\Http\Controllers\BaseCrudHandler;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     path="/api/warehouses/documents/receipt-docs/get-create-update-data",
 *     operationId="getWarehouseReceiptDocumentCreateUpdateData",
 *     tags={"WarehouseDocuments"},
 *     summary="Get warehouse receipt document create and update data",
 *     description="Returns warehouse receipt document create and update data data",
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
 *         description="Warehouse receipt document not found"
 *     )
 * )
 */
class  GetWarehouseReceiptDocumentCreateUpdateData extends BaseCrudHandler
{

}
