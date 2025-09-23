<?php

namespace Modules\Finance\Http\Controllers\Account\DetailedAccount;

use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Modules\Finance\Http\Resources\DetailedAccount\DetailedAccountResource;
use Modules\Finance\Models\Account\AccountingDetailedAccount;
use Modules\Finance\Models\Account\AccountingSpecificAccount;
use Modules\Shared\Http\Controllers\AsStaticRunner;
use Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Shared\Services\Responder;
use OpenApi\Annotations as OA;

/**
 * @OA\Post(
 *     path="/api/workshops/accounting/detailed-accounts/store",
 *     summary="Create new detailed account",
 *     description="Create a new detailed account in the accounting system with hierarchical structure support",
 *     operationId="storeDetailedAccount",
 *     tags={"Accounting > DetailedAccounts"},
 *     @OA\RequestBody(
 *         required=true,
 *         description="Detailed account data",
 *         @OA\JsonContent(
 *             required={"code", "title", "debit_amount", "credit_amount"},
 *             @OA\Property(
 *                 property="specific_account_id",
 *                 type="integer",
 *                 example=1,
 *                 description="ID of the specific account this detailed account belongs to"
 *             ),
 *             @OA\Property(
 *                 property="parent_id",
 *                 type="integer",
 *                 nullable=true,
 *                 example=null,
 *                 description="ID of parent detailed account for hierarchical structure (null for level 1, required for level 2)"
 *             ),
 *             @OA\Property(
 *                 property="code",
 *                 type="string",
 *                 maxLength=20,
 *                 example="001",
 *                 description="Unique code for the detailed account within the tenant"
 *             ),
 *             @OA\Property(
 *                 property="title",
 *                 type="string",
 *                 maxLength=255,
 *                 example="صندوق شعبه مرکزی",
 *                 description="Unique title for the detailed account within the tenant"
 *             ),
 *             @OA\Property(
 *                 property="debit_amount",
 *                 type="number",
 *                 format="decimal",
 *                 example=10000.50,
 *                 description="Current debit_amount amount for this account"
 *             ),
 *             @OA\Property(
 *                 property="total_debit_amount",
 *                 type="number",
 *                 format="decimal",
 *                 example=15000.75,
 *                 description="Total debit_amount amount including sub-accounts"
 *             ),
 *             @OA\Property(
 *                 property="credit_amount",
 *                 type="number",
 *                 format="decimal",
 *                 example=5000.25,
 *                 description="Current credit_amount amount for this account"
 *             ),
 *             @OA\Property(
 *                 property="total_credit_amount",
 *                 type="number",
 *                 format="decimal",
 *                 example=8000.50,
 *                 description="Total credit_amount amount including sub-accounts"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *          response=201,
 *          description="Detailed account created successfully",
 *          @OA\JsonContent(
 *              type="object",
 *              @OA\Property(property="success", type="boolean", example=true),
 *              @OA\Property(property="message", type="string", example="عملیات با موفقیت انجام شد"),
 *          )
 *      ),
 * )
 */
class StoreDetailedAccount extends BaseCrudHandler
{
    use AsStaticRunner;
    public function execute(array $attributes = [])
    {
        if (!is_null($attributes['parent_id'])) {
            $attributes['specific_account_id'] = AccountingDetailedAccount::whereId($attributes['parent_id'])->first()->specific_account_id;
        }

        $detailedAccount = AccountingDetailedAccount::create([
            'tenant_id' => $this->tenant?->id,
            'specific_account_id' => $attributes['specific_account_id'] ,
            'parent_id' => $attributes['parent_id'] ?? null,
            'code' => $attributes['code'],
            'title' => $attributes['title'],
            'level' => !is_null($attributes['parent_id']) ? AccountingDetailedAccount::LEVEL_2 : AccountingDetailedAccount::LEVEL_1,
        ]);

        return Responder::success([
            'detailed_account' => new DetailedAccountResource($detailedAccount)
        ]);
    }

    public function authorize()
    {
        return true;
    }

    public function validate()
    {
        return [
            'specific_account_id' => ['nullable', Rule::requiredIf(is_null('parent_id')), Rule::exists('accounting_specific_accounts', 'id')->where(function ($query) {
                $query->where('tenant_id', $this->tenant?->id);
            })],
            'parent_id' => ['nullable', Rule::requiredIf(is_null('specific_account_id')), Rule::exists('accounting_detailed_accounts', 'id')->where(function ($query) {
                $query->where('tenant_id', $this->tenant?->id);
            })],
            'code' => ['required', Rule::unique('accounting_detailed_accounts', 'code')->where(function ($query) {
                $query->where('tenant_id', $this->tenant?->id);
            })],
            'title' => ['required', Rule::unique('accounting_detailed_accounts', 'title')->where(function ($query) {
                $query->where('tenant_id', $this->tenant?->id);
            })],
        ];
    }

    public function afterValidator(array $attributes)
    {
        if (!is_null($attributes['specific_account_id']) && !is_null($attributes['parent_id'])) {
            throw ValidationException::withMessages([
                'حساب تفصیلی همزمان نمی تواند زیر مجموعه ی حساب معین و حساب تفصیلی دیگر باشد'
            ]);
        }

        if (is_null($this->request->specific_account_id) && is_null($this->request->parent_id)) {
            throw ValidationException::withMessages([
                'حساب تفصیلی باید زیر مجموعه ی حساب معین یا حساب تفصیلی دیگر ساخته شود'
            ]);
        }
    }
}
