<?php

namespace Modules\Finance\Http\Controllers\Account\GeneralAccount;

use Modules\Finance\Http\Resources\AccountGroup\AccountGroupResource;
use Modules\Finance\Models\Account\AccountingAccountGroup;
use Modules\Finance\Models\Account\AccountingGeneralAccount;
use Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Shared\Services\Responder;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     path="/api/workshops/accounting/general-accounts/get-create-update-data",
 *     operationId="getGeneralAccountCreatedData",
 *     tags={"Accounting > GeneralAccounts"},
 *     summary="Get general account create update data",
 *     description="Returns general account create update data for the tenant",
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
class GetGeneralAccountCreateUpdateData extends BaseCrudHandler
{
    public function handle(array $attributes = [])
    {
        $accountGroups = AccountingAccountGroup::ForTenant($this->tenant?->id)->get();

        $latestGeneralAccount = AccountingGeneralAccount::ForTenant($this->tenant->id)->latest()->first();
        $code = $latestGeneralAccount?->code ? $latestGeneralAccount->code + 1 : 10;

        return Responder::success([
            'account_groups' => AccountGroupResource::collection($accountGroups),
            'code' => $code < 10 ? "0{$code}" : $code,
            'natures' => AccountingGeneralAccount::getTranslatedNatures(),
            'statuses' => AccountingGeneralAccount::getTranslatedStatuses(),
        ]);
    }

    public function authorize()
    {
        return true;
    }
}
