<?php

namespace Modules\Finance\Http\Controllers\Account\DetailedAccount;

use Modules\Finance\Http\Resources\SpecificAccount\SpecificAccountResource;
use Modules\Finance\Models\Account\AccountingDetailedAccount;
use Modules\Finance\Models\Account\AccountingSpecificAccount;
use Modules\Finance\Models\Document\AccountingDocumentArticle;
use Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Shared\Services\Responder;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     path="/api/workshops/accounting/detailed-accounts/get-create-update-data",
 *     operationId="getDetailedAccountCreatedData",
 *     tags={"Accounting > DetailedAccounts"},
 *     summary="Get detailed account create update data",
 *     description="Returns detailed account create update data for the tenant",
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
class GetDetailedAccountCreateUpdateData extends BaseCrudHandler
{
    public function handle(array $attributes = [])
    {
        $exceptDetailedAccounts = AccountingDocumentArticle::ForTenant($this->tenant?->id)->get()->pluck('detailed_account_id')->toArray();

        $specificAccounts = AccountingSpecificAccount::ForTenant($this->tenant?->id)->get();

        $detailedAccounts = AccountingDetailedAccount::ForTenant($this->tenant?->id)
            ->whereNotIn('id', $exceptDetailedAccounts)
            ->get();

        $latestDetailedAccount = AccountingDetailedAccount::ForTenant($this->tenant->id)->latest()->first();
        $code = $latestDetailedAccount?->code ? $latestDetailedAccount->code + 1 : 10;

        return Responder::success([
            'specific_accounts' => SpecificAccountResource::collection($specificAccounts),
            'detailed_accounts' => SpecificAccountResource::collection($detailedAccounts),
            'code' => $code < 10 ? "0{$code}" : $code,
            'statuses' => AccountingDetailedAccount::getTranslatedStatuses()
        ]);
    }

    public function authorize()
    {
        return true;
    }
}
