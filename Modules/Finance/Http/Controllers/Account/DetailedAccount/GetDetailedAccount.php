<?php

namespace Modules\Finance\Http\Controllers\Account\DetailedAccount;

use Modules\Finance\Http\Resources\DetailedAccount\DetailedAccountResource;
use Modules\Finance\Models\Account\AccountingDetailedAccount;
use Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Shared\Services\Responder;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     path="/api/workshops/accounting/detailed-accounts/{id}/get",
 *     operationId="getDetailedAccount",
 *     tags={"Accounting > DetailedAccounts"},
 *     summary="Get a detailed account information",
 *     description="Returns a detailed account information for the tenant",
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
class GetDetailedAccount extends BaseCrudHandler
{
    public function handle(array $attributes = [])
    {
        $detailedAccount = AccountingDetailedAccount::ForTenant($this->tenant?->id)
            ->findOrFail($attributes['id']);

        return Responder::success([
            'detailed_account' => new DetailedAccountResource($detailedAccount)
        ]);
    }

    public function authorize()
    {
        return true;
    }
}
