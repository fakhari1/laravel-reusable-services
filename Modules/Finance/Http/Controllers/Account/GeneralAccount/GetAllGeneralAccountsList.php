<?php

namespace Modules\Finance\Http\Controllers\Account\GeneralAccount;

use Modules\Finance\Http\Resources\GeneralAccount\GeneralAccountCollection;
use Modules\Finance\Models\Account\AccountingGeneralAccount;
use Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Shared\Services\Responder;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     path="/api/workshops/accounting/general-accounts/all",
 *     operationId="getAllGeneralAccountsList",
 *     tags={"Accounting > GeneralAccounts"},
 *     summary="Get list of general accounts",
 *     description="Returns list of general accounts for the tenant",
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="data"
 *             )
 *         )
 *     )
 * )
 */
class GetAllGeneralAccountsList extends BaseCrudHandler
{
    public function handle(array $attributes = [])
    {
        $tenantId = $this->tenant?->id;

        $query = AccountingGeneralAccount::ForTenant($tenantId)->with('accountGroup');

        if ($this->request->filled('group_id')) {
            $query->ForGroup($this->request->has('group_id'));
        }

        if (isset($attributes['nature'])) {
            $query->where('nature', $attributes['nature']);
        }

        if (isset($attributes['status'])) {
            $query->where('status', $attributes['status']);
        }

        if ($this->request->filled('search')) {
            $search = $this->request->get('search');

            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                    ->orWhere('code', 'LIKE', "%{$search}%");
            });
        }

        $generalAccounts = $query->paginate(20);

        return Responder::success(new GeneralAccountCollection($generalAccounts));
    }

    public function authorize()
    {
        return true;
    }
}
