<?php

namespace Modules\Finance\Http\Controllers\Account\GeneralAccount;

use Modules\Finance\Models\Account\AccountingGeneralAccount;
use Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Shared\Services\Responder;
use OpenApi\Annotations as OA;

/**
 * @OA\Delete(
 *     path="/api/workshops/accounting/general-accounts/{id}/delete",
 *     summary="Delete an general account",
 *     operationId="deleteGeneralAccount",
 *     tags={"Accounting > GeneralAccounts"},
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
class DestroyGeneralAccount extends BaseCrudHandler
{
    public function execute(array $attributes = [])
    {
        $generalAccount = AccountingGeneralAccount::ForTenant($this->tenant?->id)
            ->findOrFail($attributes['id']);

        if ($generalAccount->specificAccounts()->exists()) {
            return Responder::error('نمی‌توان حساب کل را حذف کرد زیرا دارای حساب‌های معین وابسته است');
        }

        if ($generalAccount->total_debit_amount > 0) {
            return Responder::error('نمی‌توان حساب کل را حذف کرد زیرا مبلغ بدهکار بزرگتر از 0 است');
        }

        if ($generalAccount->total_credit_amount > 0) {
            return Responder::error('نمی‌توان حساب کل را حذف کرد زیرا مبلغ بستانکار بزرگتر از 0 است');
        }

        return Responder::success($generalAccount->delete());
    }

    public function authorize()
    {
        return true;
    }
}
