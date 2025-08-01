<?php

namespace Modules\Modules\Warehouse\Http\Controllers\Api\Document\Transfer;

use Modules\Identity\Http\Resources\StaffResource;
use Modules\Identity\Models\TenantStaff;
use Modules\Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Modules\Shared\Services\Responder;
use Modules\Modules\Warehouse\Http\Resources\Product\ProductResource;
use Modules\Modules\Warehouse\Http\Resources\Warehouse\WarehouseResource;
use Modules\Modules\Warehouse\Models\Product;
use Modules\Modules\Warehouse\Models\Warehouse;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     path="/api/warehouses/documents/transfer-docs/get-create-update-data",
 *     operationId="getTransferDocumentCreateData",
 *     tags={"WarehouseDocuments"},
 *     summary="Get transfer document create data",
 *     description="Returns transfer document create data data",
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
class GetWarehouseTransferDocumentCreateUpdateData extends BaseCrudHandler
{
    public function handle(array $attributes = [])
    {
        $tenantId = $this->tenant?->id;

        $warehouses = Warehouse::ForTenant($tenantId)->with('racks')->get();
        $products = Product::ForTenant($tenantId)->get();
        $staff = TenantStaff::ForTenant($tenantId)->get();

        return Responder::success([
            'warehouses' => WarehouseResource::collection($warehouses),
            'products' => ProductResource::collection($products),
            'staff' => StaffResource::collection($staff),
        ]);
    }
}
