<?php

namespace Modules\Finance\Http\Controllers\Account\DetailedAccount;

use Modules\Finance\Models\Account\AccountingDetailedAccount;
use Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Shared\Services\Responder;
use OpenApi\Annotations as OA;

/**
 * @OA\Delete(
 *     path="/api/workshops/accounting/detailed-accounts/{id}/delete",
 *     summary="Delete a detailed account",
 *     operationId="deleteDetailedAccount",
 *     tags={"Accounting > DetailedAccounts"},
 *     @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 *          @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Detailed account deleted successfully"
 *     )
 * )
 */
class DestroyDetailedAccount extends BaseCrudHandler
{
    public function execute(array $attributes = [])
    {
        $detailedAccount = AccountingDetailedAccount::ForTenant($this->tenant?->id)
            ->findOrFail($attributes['id']);

        if ($detailedAccount->children()->exists()) {
            return Responder::error('نمی‌توان حساب تفصیلی را حذف کرد زیرا دارای حساب‌های تفصیلی وابسته است');
        }

        return Responder::success($detailedAccount->delete());
    }

    public function authorize()
    {
        return true;
    }
}
