<?php

namespace Modules\Finance\Http\Controllers\Account\SpecificAccount;

use Modules\Finance\Models\Account\AccountingSpecificAccount;
use Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Shared\Services\Responder;
use OpenApi\Annotations as OA;

/**
 * @OA\Delete(
 *     path="/api/workshops/accounting/specific-accounts/{id}/delete",
 *     summary="Delete an specific account",
 *     operationId="deleteSpecificAccount",
 *     tags={"Accounting > SpecificAccounts"},
 *     @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 *          @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Specific account deleted successfully"
 *     )
 * )
 */
class DestroySpecificAccount extends BaseCrudHandler
{
    public function execute(array $attributes = [])
    {
        $specificAccount = AccountingSpecificAccount::ForTenant($this->tenant?->id)
            ->findOrFail($attributes['id']);

        if ($specificAccount->detailedAccounts()->exists()) {
            return Responder::error('نمی‌توان حساب معین را حذف کرد زیرا دارای حساب‌های تفصیلی وابسته است');
        }

        if ($specificAccount->total_debit_amount > 0) {
            return Responder::error('نمی‌توان حساب کل را حذف کرد زیرا مبلغ بدهکار بزرگتر از 0 است');
        }

        if ($specificAccount->total_credit_amount > 0) {
            return Responder::error('نمی‌توان حساب کل را حذف کرد زیرا مبلغ بستانکار بزرگتر از 0 است');
        }


        return Responder::success($specificAccount->delete());
    }

    public function authorize()
    {
        return true;
    }
}
