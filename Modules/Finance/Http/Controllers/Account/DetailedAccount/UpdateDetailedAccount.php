<?php

namespace Modules\Finance\Http\Controllers\Account\DetailedAccount;

use Illuminate\Validation\Rule;
use Modules\Finance\Http\Resources\DetailedAccount\DetailedAccountResource;
use Modules\Finance\Models\Account\AccountingDetailedAccount;
use Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Shared\Services\Responder;
use OpenApi\Annotations as OA;

/**
 * @OA\Put(
 *     path="/api/workshops/accounting/detailed-accounts/{id}/update",
 *     summary="Update detailed account",
 *     description="Update an existing detailed account's code and title.",
 *     operationId="updateDetailedAccount",
 *     tags={"Accounting > DetailedAccounts"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the detailed account to update",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         description="Updated detailed account data",
 *         @OA\JsonContent(
 *             required={"code", "title"},
 *             @OA\Property(
 *                 property="code",
 *                 type="string",
 *                 maxLength=20,
 *                 example="002",
 *                 description="Updated unique code for the detailed account within the tenant"
 *             ),
 *             @OA\Property(
 *                 property="title",
 *                 type="string",
 *                 maxLength=255,
 *                 example="صندوق شعبه شمال",
 *                 description="Updated unique title for the detailed account within the tenant"
 *             )
 *         )
 *     ),
 *          @OA\Response(
 *           response=200,
 *           description="Detailed account updated successfully",
 *           @OA\JsonContent(
 *               type="object",
 *               @OA\Property(property="success", type="boolean", example=true),
 *               @OA\Property(property="message", type="string", example="عملیات با موفقیت انجام شد"),
 *           )
 *       ),
 * )
 */
class UpdateDetailedAccount extends BaseCrudHandler
{
    public function execute(array $attributes = [])
    {
        $detailedAccount = AccountingDetailedAccount::create([
            'code' => $attributes['code'],
            'title' => $attributes['title'],
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
            'code' => ['required', Rule::unique('accounting_detailed_accounts', 'code')->where(function ($query) {
                return $query->where('tenant_id', $this->tenant?->id);
            })->ignore($this->request->id)],
            'title' => ['required', Rule::unique('accounting_detailed_accounts', 'title')->where(function ($query) {
                return $query->where('tenant_id', $this->tenant?->id);
            })->ignore($this->request?->id)],
        ];
    }
}
