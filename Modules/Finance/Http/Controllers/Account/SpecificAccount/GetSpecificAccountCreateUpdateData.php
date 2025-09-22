<?php

namespace Modules\Finance\Http\Controllers\Account\SpecificAccount;

use Modules\Finance\Http\Resources\GeneralAccount\GeneralAccountResource;
use Modules\Finance\Models\Account\AccountingGeneralAccount;
use Modules\Finance\Models\Account\AccountingSpecificAccount;
use Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Shared\Services\Responder;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     path="/api/workshops/accounting/specific-accounts/get-create-update-data",
 *     operationId="getSpecificAccountCreateUpdateData",
 *     tags={"Accounting > SpecificAccounts"},
 *     summary="Get specific account create update data",
 *     description="Returns specific account create update data for the tenant",
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
class GetSpecificAccountCreateUpdateData extends BaseCrudHandler
{
    public function handle(array $attributes = [])
    {
        $generalAccounts = AccountingGeneralAccount::ForTenant($this->tenant->id)->get();

        $latestSpecificAccount = AccountingSpecificAccount::ForTenant($this->tenant->id)->latest()->first();
        $code = $latestSpecificAccount?->code ? $latestSpecificAccount->code + 1 : 10;

        return Responder::success([
            'code' => $code < 10 ? "0{$code}" : $code,
            'general_accounts' => GeneralAccountResource::collection($generalAccounts),
            'natures' => AccountingSpecificAccount::getTranslatedNatures(),
            'statuses' => AccountingSpecificAccount::getTranslatedStatuses(),
        ]);
    }

    public function authorize()
    {
        return true;
    }
}
