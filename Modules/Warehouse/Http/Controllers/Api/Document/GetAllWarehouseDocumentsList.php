<?php

namespace Modules\Warehouse\Http\Controllers\Api\Document;

use Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Shared\Services\Responder;
use Modules\Warehouse\Http\Resources\WarehouseDocument\WarehouseDocumentCollection;
use Modules\Warehouse\Models\WarehouseDocument;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     path="/api/warehouses/documents/all",
 *     operationId="getWarehouseDocumentsList",
 *     tags={"Warehouse > Documents"},
 *     summary="Get list of warehouse documents",
 *     description="Returns list of warehouse documents for the tenant",
 *     @OA\Parameter(
 *         name="page",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="integer"),
 *         description="Page number"
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="data",
 *                 type="string"),
 *             @OA\Property(
 *                 property="meta",
 *                 type="object",
 *                 @OA\Property(property="total", type="integer"),
 *                 @OA\Property(property="per_page", type="integer"),
 *                 @OA\Property(property="current_page", type="integer"),
 *                 @OA\Property(property="last_page", type="integer"),
 *                 @OA\Property(property="from", type="integer"),
 *                 @OA\Property(property="to", type="integer")
 *             ),
 *             @OA\Property(
 *                 property="links",
 *                 type="object",
 *                 @OA\Property(property="first", type="string"),
 *                 @OA\Property(property="last", type="string"),
 *                 @OA\Property(property="prev", type="string"),
 *                 @OA\Property(property="next", type="string")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized"
 *     )
 * )
 */
class GetAllWarehouseDocumentsList extends BaseCrudHandler
{
    public function handle(array $attributes = [])
    {
        $tenantId = $this->tenant?->id;

        $query = WarehouseDocument::ForTenant($tenantId)->with(['staff', 'warehouse', 'documentable', 'products']);

        if (isset($attributes['type'])) {
            $query->where('type', $attributes['type']);
        }

        if (isset($attributes['started_at'])) {
            $query->where('created_at', '>=', $attributes['started_at']);
        }

        if (isset($attributes['finished_at'])) {
            $query->where('created_at', '<=', $attributes['finished_at']);
        }

        if ($this->shouldPaginate()) {
            $paginationParams = $this->getPaginationParams();

            $warehouseDocuments = $query->paginate(
                $paginationParams['per_page'],
                ['*'],
                'page',
                $paginationParams['page']
            );
        } else {
            $warehouseDocuments = $query->orderBy('created_at', 'desc')->get();
        }


        return Responder::success(new WarehouseDocumentCollection($warehouseDocuments));
    }

    public function authorize()
    {
        return true;
    }
}
