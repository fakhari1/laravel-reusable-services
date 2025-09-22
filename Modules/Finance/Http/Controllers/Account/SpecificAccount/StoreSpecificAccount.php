<?php

namespace Modules\Finance\Http\Controllers\Account\SpecificAccount;

use Illuminate\Validation\Rule;
use Modules\Finance\Http\Resources\SpecificAccount\SpecificAccountResource;
use Modules\Finance\Models\Account\AccountingSpecificAccount;
use Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Shared\Services\Responder;
use OpenApi\Annotations as OA;

/**
 * @OA\Post(
 *     path="/api/workshops/accounting/specific-accounts/store",
 *     operationId="storeSpecificAccount",
 *     tags={"Accounting > SpecificAccounts"},
 *     summary="Create a new specific account",
 *     description="Create a new accounting specific account",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"general_account_id", "code", "title", "nature"},
 *             @OA\Property(property="general_account_id", type="integer", example=1, description="ID of the general account"),
 *             @OA\Property(property="code", type="string", example="1101001", description="Specific account code"),
 *             @OA\Property(property="title", type="string", example="Bank Account - ABC Bank", description="Specific account title"),
 *             @OA\Property(property="nature", type="string", example="بدهکار | بستانکار"),
 *             @OA\Property(property="status", type="string", enum={"active", "inactive"}, example="active", description="Account status"),
 *             @OA\Property(property="description", type="string", example="Main bank account for operations", description="Optional description")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Specific account created successfully"
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error"
 *     )
 * )
 */
class StoreSpecificAccount extends BaseCrudHandler
{
    public function execute(array $attributes = [])
    {
        $specificAccount = AccountingSpecificAccount::create([
            'tenant_id' => $this->tenant?->id,
            'general_account_id' => $attributes['general_account_id'],
            'total_debit_amount' => 0,
            'total_credit_amount' => 0,
            'code' => $attributes['code'],
            'title' => $attributes['title'],
            'status' => $attributes['status'],
            'description' => $attributes['description'] ?? null,
        ]);

        return Responder::success([
            'specific_account' => new SpecificAccountResource($specificAccount)
        ]);
    }

    public function authorize()
    {
        return true;
    }

    public function validate()
    {
        return [
            'general_account_id' => ['required', Rule::exists('accounting_general_accounts', 'id')->where(function ($query) {
                $query->where('tenant_id', $this->tenant?->id);
            })],
            'code' => ['required', Rule::unique('accounting_specific_accounts', 'code')->where(function ($query) {
                $query->where('tenant_id', $this->tenant?->id);
            })],
            'title' => ['required', Rule::unique('accounting_specific_accounts', 'title')->where(function ($query) {
                $query->where('tenant_id', $this->tenant?->id);
            })],
            'status' => ['required'],
            'description' => ['nullable']
        ];
    }
}
