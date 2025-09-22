<?php

namespace Modules\Finance\Http\Controllers\Account\AccountGroup;

use Modules\Finance\Http\Resources\AccountGroup\AccountGroupResource;
use Modules\Finance\Models\Account\AccountingAccountGroup;
use Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Shared\Services\Responder;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     path="/api/workshops/accounting/account-groups/{id}/get",
 *     operationId="getAccountGroup",
 *     tags={"Accounting > AccountGroups"},
 *     summary="Get account group information",
 *     description="Returns account group information for the tenant",
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="data",
 *             )
 *         )
 *     )
 * )
 */
class GetAccountGroup extends BaseCrudHandler
{
    public function handle(array $attributes = [])
    {
        $tenantId = $this->tenant?->id;

        $accountGroup = AccountingAccountGroup::ForTenant($tenantId)->findOrFail($attributes['id']);

        return Responder::success(new AccountGroupResource($accountGroup));
    }

    public function authorize()
    {
        return true;
    }
}
