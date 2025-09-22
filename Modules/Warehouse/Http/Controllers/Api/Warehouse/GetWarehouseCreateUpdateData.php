<?php

namespace Modules\Warehouse\Http\Controllers\Api\Warehouse;

use Modules\Finance\Http\Resources\AccountingDetailedAccountResource;
use Modules\Identity\Http\Resources\StaffResource;
use Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Shared\Services\Responder;
use Modules\Warehouse\Models\Warehouse;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     path="/api/warehouses/get-create-update-data",
 *     operationId="getWarehouseCreateUpdateData",
 *     tags={"Warehouse > Warehouses"},
 *     summary="Get warehouse create and update data",
 *     description="Returns warehouse create and update data data",
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="Forbidden"
 *     ),
 * )
 */
class GetWarehouseCreateUpdateData extends BaseCrudHandler
{
    /**
     * Handle the incoming request.
     */
    public function handle(array $attributes = [])
    {
        $staff = $this->tenant->staff;
//        $accountingDetailedAccounts = AccountingDetailedAccount::ForTenant($this->tenant->id)->doesntHave('children')->get();

        return Responder::success([
            'code' => Warehouse::ForTenant($this->tenant?->id)->count() + 1,
            'staff' => StaffResource::collection($staff),
            'default_storekeeper' => new StaffResource(auth('api-tenant')->user()),
//            'accounts' => AccountingDetailedAccountResource::collection($accountingDetailedAccounts)
            'accounts' => []
        ]);
    }

    public function authorize()
    {
        return true;
    }
}
