<?php

namespace Modules\Finance\Http\Controllers\Account\AccountGroup;

use Illuminate\Validation\Rule;
use Modules\Finance\Models\Account\AccountingAccountGroup;
use Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Shared\Services\Responder;
use OpenApi\Annotations as OA;

/**
 * @OA\Delete(
 *     path="/api/workshops/accounting/account-groups/{id}/delete",
 *     summary="Delete an account group",
 *     operationId="deleteAccountGroup",
 *     tags={"Accounting > AccountGroups"},
 *     @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 *          @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Account group deleted successfully"
 *     )
 * )
 */
class DestroyAccountGroup extends BaseCrudHandler
{
    public function execute(array $attributes = [])
    {
        $accountGroup = AccountingAccountGroup::ForTenant($this->tenant?->id)
            ->findOrFail($attributes['id']);

        if ($accountGroup->generalAccounts()->exists()) {
            return Responder::error('نمی‌توان گروه حساب را حذف کرد زیرا دارای حساب‌های کل وابسته است');
        }

        return Responder::success($accountGroup->delete());
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
            })->ignore($this->request->get('id'))],
            'nature' => ['required', Rule::in(AccountingAccountGroup::$natures)],
            'type' => ['required', Rule::in(AccountingAccountGroup::$types)],
        ];
    }

}
