<?php

namespace Modules\Finance\Http\Controllers\Account\AccountGroup;

use Modules\Finance\Models\Account\AccountingAccountGroup;
use Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Shared\Services\Responder;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     path="/api/workshops/accounting/account-groups/get-create-update-data",
 *     operationId="getAccountGroupCreateUpdateData",
 *     tags={"Accounting > AccountGroups"},
 *     summary="Get account group create update data",
 *     description="Returns account group create update data for the tenant",
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
class GetAccountGroupCreateUpdateData extends BaseCrudHandler
{
    public function handle(array $attributes = [])
    {
        return Responder::success([
            'natures' => AccountingAccountGroup::getTranslatedNatures(),
            'types' => AccountingAccountGroup::getTranslatedTypes(),
        ]);
    }

    public function authorize()
    {
        return true;
    }
}
