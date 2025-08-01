<?php

namespace Modules\Modules\Warehouse\Http\Controllers\Api\Warehouse;

use Modules\Finance\Http\Resources\AccountingDetailedAccountResource;
use Modules\Finance\Models\AccountingDetailedAccount;
use Modules\Identity\Http\Resources\StaffResource;
use Modules\Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Modules\Shared\Services\Responder;
use OpenApi\Annotations as OA;
use function Modules\Warehouse\Http\Controllers\Api\Warehouse\auth;

/**
 * @OA\Get(
 *     path="/api/warehouses/get-create-update-data",
 *     operationId="getWarehouseCreateUpdateData",
 *     tags={"Warehouses"},
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
 *     @OA\Response(
 *         response=404,
 *         description="Warehouse not found"
 *     )
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
            'staff' => StaffResource::collection($staff),
            'default_storekeeper' => new StaffResource(auth('api-tenant')->user()),
//            'accounts' => AccountingDetailedAccountResource::collection($accountingDetailedAccounts)
            'accounts' => []
        ]);
    }
}
