<?php

namespace Modules\Finance\Http\Controllers\Account\GeneralAccount;

use Illuminate\Validation\Rule;
use Modules\Finance\Http\Resources\GeneralAccount\GeneralAccountResource;
use Modules\Finance\Models\Account\AccountingGeneralAccount;
use Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Shared\Services\Responder;
use OpenApi\Annotations as OA;

/**
 * @OA\Post(
 *     path="/api/workshops/accounting/general-accounts/store",
 *     summary="Create a new general account",
 *     operationId="storeGeneralAccount",
 *     tags={"Accounting > GeneralAccounts"},
 *          @OA\RequestBody(
 *          required=true,
 *          @OA\JsonContent(
 *              required={"group_id","code","title","nature"},
 *              @OA\Property(property="group_id", type="integer", example="1"),
 *              @OA\Property(property="code", type="string", example="1001"),
 *              @OA\Property(property="title", type="string", example="Cash"),
 *              @OA\Property(property="nature", type="string", example="debtor"),
 *              @OA\Property(property="status", type="string", example="active"),
 *              @OA\Property(property="description", type="string", example="Cash account description", nullable=true)
 *          ),
 *      ),
 *     @OA\Response(
 *         response=201,
 *         description="Account group created successfully"
 *     )
 * )
 */
class StoreGeneralAccount extends BaseCrudHandler
{
    public function execute(array $attributes = [])
    {
        $generalAccount = AccountingGeneralAccount::create([
            'tenant_id' => $this->tenant?->id,
            'group_id' => $attributes['group_id'],
            'code' => $attributes['code'],
            'title' => $attributes['title'],
            'nature' => $attributes['nature'],
            'total_debit_amount' => 0,
            'total_credit_amount' => 0,
            'status' => $attributes['status'],
            'description' => $attributes['description'] ?? null,
        ]);

        return Responder::success([
            'general_account' => new GeneralAccountResource($generalAccount)
        ]);
    }

    public function authorize()
    {
        return true;
    }

    public function validate()
    {
        return [
            'group_id' => ['required', Rule::exists('accounting_account_groups', 'id')->where(function ($query) {
                $query->where('tenant_id', $this->tenant?->id);
            })],
            'code' => ['required', Rule::unique('accounting_general_accounts', 'code')->where(function ($query) {
                $query->where('tenant_id', $this->tenant?->id);
            })],
            'title' => ['required', Rule::unique('accounting_general_accounts', 'title')->where(function ($query) {
                $query->where('tenant_id', $this->tenant?->id);
            })],
            'nature' => ['required', Rule::in(AccountingGeneralAccount::$natures)],
            'status' => ['required'],
            'description' => ['nullable']
        ];
    }
}
