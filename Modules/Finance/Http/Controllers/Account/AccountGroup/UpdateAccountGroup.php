<?php

namespace Modules\Finance\Http\Controllers\Account\AccountGroup;

use Illuminate\Validation\Rule;
use Modules\Finance\Http\Resources\AccountGroup\AccountGroupResource;
use Modules\Finance\Models\Account\AccountingAccountGroup;
use Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Shared\Services\Responder;
use OpenApi\Annotations as OA;

/**
 * @OA\Put(
 *     path="/api/workshops/accounting/account-groups/{id}/update",
 *     summary="Update an account group",
 *     operationId="updateAccountGroup",
 *     tags={"Accounting > AccountGroups"},
 *     @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 *          @OA\Schema(type="integer")
 *     ),
 *          @OA\RequestBody(
 *          required=true,
 *          @OA\JsonContent(
 *               required={"title", "nature", "type"},
 *               @OA\Property(property="title", type="string", example="Assets"),
 *               @OA\Property(property="nature", type="string", example="debtor"),
 *               @OA\Property(property="type", type="string", example="current")
 *          )
 *      ),
 *     @OA\Response(
 *         response=200,
 *         description="Account group updated successfully"
 *     )
 * )
 */
class UpdateAccountGroup extends BaseCrudHandler
{
    public function execute(array $attributes = [])
    {
        $accountGroup = AccountingAccountGroup::ForTenant($this->tenant?->id)
            ->findOrFail($attributes['id']);

        $accountGroup->update([
            'title' => $attributes['title'],
            'nature' => $attributes['nature'],
            'type' => $attributes['type'],
        ]);

        return Responder::success([
            'account_group' => new AccountGroupResource($accountGroup->fresh())
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
                return $query->where('tenant_id', $this->tenant?->id);
            })->ignore($this->request->id)],
            'nature' => ['required', Rule::in(AccountingAccountGroup::$natures)],
            'type' => ['required', Rule::in(AccountingAccountGroup::$types)],
        ];
    }

}
