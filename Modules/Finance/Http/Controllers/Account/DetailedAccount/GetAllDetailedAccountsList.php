<?php

namespace Modules\Finance\Http\Controllers\Account\DetailedAccount;

use Modules\Finance\Http\Resources\DetailedAccount\DetailedAccountCollection;
use Modules\Finance\Models\Account\AccountingDetailedAccount;
use Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Shared\Services\Responder;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     path="/api/workshops/accounting/detailed-accounts/all",
 *     operationId="getAllDetailedAccountsList",
 *     tags={"Accounting > DetailedAccounts"},
 *     summary="Get list of detailed accounts",
 *     description="Returns list of detailed accounts for the tenant",
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
class GetAllDetailedAccountsList extends BaseCrudHandler
{
    public function handle(array $attributes = [])
    {
        $tenantId = $this->tenant?->id;

        $query = AccountingDetailedAccount::ForTenant($tenantId)->with('children');


        if ($this->request->has('search')) {
            $search = $this->request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('code', 'LIKE', "%{$search}%");
            });
        }

        $detailedAccounts = $query->paginate(20);

        return Responder::success(new DetailedAccountCollection($detailedAccounts));
    }

    public function authorize()
    {
        return true;
    }
}
