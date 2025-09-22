<?php

namespace Modules\Finance\Http\Controllers\Account\AccountGroup;

use Illuminate\Validation\Rule;
use Modules\Finance\Http\Resources\AccountGroup\AccountGroupResource;
use Modules\Finance\Models\Account\AccountingAccountGroup;
use Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Shared\Services\Responder;
use OpenApi\Annotations as OA;

/**
 * @OA\Post(
 *     path="/api/workshops/accounting/account-groups/store",
 *     summary="Create a new account group",
 *     operationId="storeAccountGroup",
 *     tags={"Accounting > AccountGroups"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *              required={"title", "nature", "type"},
 *              @OA\Property(property="title", type="string", example="Assets"),
 *              @OA\Property(property="nature", type="string", example="debtor"),
 *              @OA\Property(property="type", type="string", example="current")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Account group created successfully"
 *     )
 * )
 */
class StoreAccountGroup extends BaseCrudHandler
{
    public function execute(array $attributes = [])
    {
        $accountGroup = AccountingAccountGroup::create([
            'tenant_id' => $this->tenant?->id,
            'title' => $attributes['title'],
            'nature' => $attributes['nature'],
            'type' => $attributes['type'],
        ]);

        return Responder::success([
            'account_group' => new AccountGroupResource($accountGroup)
        ]);
    }

    public function authorize()
    {
        return true;
    }

    public function validate()
    {
        return [
            'title' => ['required', Rule::unique('accounting_account_groups', 'title')->where(function ($query) {
                $query->where('tenant_id', $this->tenant?->id);
            })],
            'nature' => ['required', Rule::in(AccountingAccountGroup::$natures)],
            'type' => ['required', Rule::in(AccountingAccountGroup::$types)],
        ];
    }

}
