<?php

namespace Modules\Finance\Http\Controllers\Account\SpecificAccount;

use Modules\Finance\Http\Resources\SpecificAccount\SpecificAccountCollection;
use Modules\Finance\Models\Account\AccountingSpecificAccount;
use Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Shared\Services\Responder;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     path="/api/workshops/accounting/specific-accounts/all",
 *     operationId="getAllSpecificAccountsList",
 *     tags={"Accounting > SpecificAccounts"},
 *     summary="Get list of specific accounts",
 *     description="Returns list of specific accounts for the tenant",
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
class GetAllSpecificAccountsList extends BaseCrudHandler
{
    public function handle(array $attributes = [])
    {
        $tenantId = $this->tenant?->id;

        $query = AccountingSpecificAccount::ForTenant($tenantId)->with('detailedAccounts');

        if ($this->request->filled('general_account_id')) {
            $query->where('general_account_id', $this->request->general_account_id);
        }

        if ($this->request->filled('status')) {
            $query->where('status', $this->request->status);
        }


        if ($this->request->filled('search')) {
            $search = $this->request->get('search');

            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                    ->orWhere('code', 'LIKE', "%{$search}%");
            });
        }

        $specificAccounts = $query->paginate(20);

        return Responder::success(new SpecificAccountCollection($specificAccounts));
    }

    public function authorize()
    {
        return true;
    }
}
