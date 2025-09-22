<?php

namespace Modules\Finance\Http\Controllers\Account\SpecificAccount;

use Illuminate\Validation\Rule;
use Modules\Finance\Http\Resources\SpecificAccount\SpecificAccountResource;
use Modules\Finance\Models\Account\AccountingGeneralAccount;
use Modules\Finance\Models\Account\AccountingSpecificAccount;
use Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Shared\Services\Responder;
use OpenApi\Annotations as OA;

/**
 * @OA\Put(
 *     path="/api/workshops/accounting/specific-accounts/{id}/update",
 *     operationId="updateSpecificAccount",
 *     tags={"Accounting > SpecificAccounts"},
 *     summary="Update a specific account",
 *     description="Update an existing accounting specific account",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer"),
 *         description="Specific account ID"
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="general_account_id", type="integer", example=1, description="ID of the general account"),
 *             @OA\Property(property="code", type="string", example="1101001", description="Specific account code"),
 *             @OA\Property(property="title", type="string", example="Bank Account - ABC Bank", description="Specific account title"),
 *             @OA\Property(property="status", type="string", enum={"active", "inactive"}, example="active", description="Account status"),
 *             @OA\Property(property="description", type="string", example="Main bank account for operations", description="Optional description")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Specific account updated successfully"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Specific account not found"
 *     )
 * )
 */
class UpdateSpecificAccount extends BaseCrudHandler
{
    public function execute(array $attributes = [])
    {
        $specificAccount = AccountingGeneralAccount::ForTenant($this->tenant->id)->findOrFail($attributes['id']);

        $specificAccount->update([
            'general_account_id' => $attributes['general_account_id'],
            'code' => $attributes['code'],
            'title' => $attributes['title'],
            'status' => $attributes['status'],
            'description' => $attributes['description'] ?? $specificAccount->description,
        ]);

        return Responder::success([
            'specific_account' => new SpecificAccountResource($specificAccount->fresh())
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
                return $query->where('tenant_id', $this->tenant?->id);
            })->ignore($this->request->id)],
            'title' => ['required', Rule::unique('accounting_specific_accounts', 'title')->where(function ($query) {
                return $query->where('tenant_id', $this->tenant?->id);
            })->ignore($this->request->id)],
            'status' => ['required'],
            'description' => ['nullable']
        ];
    }
}
