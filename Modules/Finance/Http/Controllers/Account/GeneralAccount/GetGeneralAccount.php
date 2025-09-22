<?php

namespace Modules\Finance\Http\Controllers\Account\GeneralAccount;

use Modules\Finance\Http\Resources\GeneralAccount\GeneralAccountResource;
use Modules\Finance\Models\Account\AccountingGeneralAccount;
use Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Shared\Services\Responder;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     path="/api/workshops/accounting/general-accounts/{id}/get",
 *     operationId="getGeneralAccount",
 *     tags={"Accounting > GeneralAccounts"},
 *     summary="Get a general account information",
 *     description="Returns a general account information for the tenant",
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
class GetGeneralAccount extends BaseCrudHandler
{
    public function handle(array $attributes = [])
    {
        $generalAccount = AccountingGeneralAccount::ForTenant($this->tenant?->id)
            ->findOrFail($attributes['id']);

        return Responder::success([
            'general_account' => new GeneralAccountResource($generalAccount)
        ]);
    }

    public function authorize()
    {
        return true;
    }
}
