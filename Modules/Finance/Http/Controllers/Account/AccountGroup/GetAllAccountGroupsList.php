<?php

namespace Modules\Finance\Http\Controllers\Account\AccountGroup;

use Modules\Finance\Http\Resources\AccountGroup\AccountGroupCollection;
use Modules\Finance\Models\Account\AccountingAccountGroup;
use Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Shared\Services\Responder;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     path="/api/workshops/accounting/account-groups/all",
 *     operationId="getAccountGroupsList",
 *     tags={"Accounting > AccountGroups"},
 *     summary="Get list of account groups",
 *     description="Returns list of account groups for the tenant",
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
class GetAllAccountGroupsList extends BaseCrudHandler
{
    public function handle(array $attributes = [])
    {
        $tenantId = $this->tenant?->id;

        $query = AccountingAccountGroup::ForTenant($tenantId);

        if ($this->request->has('nature')) {
            $query->where('nature', $attributes['nature']);
        }

        if ($this->request->has('type')) {
            $query->where('type', $attributes['type']);
        }

        if ($this->request->has('search')) {
            $search = $attributes['search'];
            $query->where('title', 'like', "%{$search}%");
        }

        $accountGroup = $query->paginate(20);

        return Responder::success(new AccountGroupCollection($accountGroup));
    }

    public function authorize()
    {
        return true;
    }
}
