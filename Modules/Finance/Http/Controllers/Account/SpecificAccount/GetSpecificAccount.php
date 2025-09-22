<?php

namespace Modules\Finance\Http\Controllers\Account\SpecificAccount;

use Modules\Finance\Http\Resources\SpecificAccount\SpecificAccountResource;
use Modules\Finance\Models\Account\AccountingSpecificAccount;
use Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Shared\Services\Responder;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     path="/api/workshops/accounting/specific-accounts/{id}/get",
 *     operationId="getSpecificAccount",
 *     tags={"Accounting > SpecificAccounts"},
 *     summary="Get a specific account information",
 *     description="Returns a specific account information for the tenant",
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
class GetSpecificAccount extends BaseCrudHandler
{
    public function handle(array $attributes = [])
    {
        $specificAccount = AccountingSpecificAccount::ForTenant($this->tenant?->id)
            ->findOrFail($attributes['id']);

        return Responder::success([
            'specific_account' => new SpecificAccountResource($specificAccount)
        ]);
    }

    public function authorize()
    {
        return true;
    }
}
