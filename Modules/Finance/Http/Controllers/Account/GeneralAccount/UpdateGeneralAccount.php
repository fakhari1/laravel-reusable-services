<?php

namespace Modules\Finance\Http\Controllers\Account\GeneralAccount;

use Illuminate\Validation\Rule;
use Modules\Finance\Http\Resources\GeneralAccount\GeneralAccountResource;
use Modules\Finance\Models\Account\AccountingGeneralAccount;
use Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Shared\Services\Responder;
use OpenApi\Annotations as OA;

/**
 * @OA\Put(
 *     path="/api/workshops/accounting/general-accounts/{id}/update",
 *     operationId="updateAccountingGeneralAccount",
 *     tags={"Accounting > GeneralAccounts"},
 *     summary="Update accounting general account",
 *     description="Returns updated accounting general account data",
 *     @OA\Parameter(
 *         name="id",
 *         description="General account id",
 *         required=true,
 *         in="path",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="group_id", type="integer", example="1"),
 *             @OA\Property(property="code", type="string", example="1001"),
 *             @OA\Property(property="title", type="string", example="Cash"),
 *             @OA\Property(property="nature", type="string", example="debtor"),
 *             @OA\Property(property="status", type="string", example="active"),
 *             @OA\Property(property="description", type="string", example="Cash account description", nullable=true)
 *         ),
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Resource not found"
 *     )
 * )
 */
class UpdateGeneralAccount extends BaseCrudHandler
{
    public function execute(array $attributes = [])
    {
        $generalAccount = AccountingGeneralAccount::ForTenant($this->tenant?->id)->findOrFail($attributes['id']);

        $generalAccount->update([
            'group_id' => $attributes['group_id'],
            'code' => $attributes['code'],
            'title' => $attributes['title'],
            'nature' => $attributes['nature'],
            'status' => $attributes['status'],
            'description' => $attributes['description'] ?? $generalAccount->description,
        ]);

        return Responder::success([
            'general_account' => new GeneralAccountResource($generalAccount->fresh())
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
                return $query->where('tenant_id', $this->tenant?->id);
            })->ignore($this->request->id)],
            'title' => ['required', Rule::unique('accounting_general_accounts', 'title')->where(function ($query) {
                return $query->where('tenant_id', $this->tenant?->id);
            })->ignore($this->request->id)],
            'nature' => ['required'],
            'status' => ['required'],
            'description' => ['nullable']
        ];
    }
}
