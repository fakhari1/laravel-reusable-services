<?php

namespace Modules\Finance\Http\Controllers\Document;

use Modules\Finance\Http\Resources\DetailedAccount\DetailedAccountCollection;
use Modules\Finance\Models\Account\AccountingDetailedAccount;
use Modules\Finance\Models\Document\AccountingDocument;
use Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Shared\Services\Responder;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     path="/api/workshops/accounting/documents/get-create-update-data",
 *     operationId="getDocumentCreateUpdateData",
 *     tags={"Accounting > Documents"},
 *     summary="Get document create update data",
 *     description="Returns document create update data for the tenant",
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="data",
 *             )
 *         )
 *     )
 * )
 */
class GetAccountingDocumentCreateUpdateData extends BaseCrudHandler
{
    public function handle(array $attributes = [])
    {
        $latestDocument = AccountingDocument::ForTenant($this->tenant->id)->latest()->first();
        $code = $latestDocument?->code ? $latestDocument->code + 1 : 1;
        $detailedAccounts = AccountingDetailedAccount::ForTenant($this->tenant->id)
            ->whereDoesntHave('children')
            ->get();

        return Responder::success([
            'detailed_accounts' => new DetailedAccountCollection($detailedAccounts),
            'code' => $code < 10 ? "0{$code}" : $code,
        ]);
    }

    public function authorize()
    {
        return true;
    }
}
