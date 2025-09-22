<?php

namespace Modules\Finance\Http\Controllers\Account\GeneralAccount;

use Modules\Finance\Http\Resources\GeneralAccount\GeneralAccountCollection;
use Modules\Finance\Models\Account\AccountingGeneralAccount;
use Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Shared\Services\Responder;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     path="/api/workshops/accounting/accounts/coding/all",
 *     operationId="getAllCodingAccountsList",
 *     tags={"Accounting > Accounts"},
 *     summary="Get list of accounts",
 *     description="Returns list of accounts for the tenant",
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
class GetAllCodingAccountsList extends BaseCrudHandler
{
    public function handle(array $attributes = [])
    {
        $tenantId = $this->tenant?->id;

        $accounts = AccountingGeneralAccount::ForTenant($tenantId)->with('accountGroup')->get();

        return Responder::success(new GeneralAccountCollection($accounts));
    }

    public function authorize()
    {
        return true;
    }
}
