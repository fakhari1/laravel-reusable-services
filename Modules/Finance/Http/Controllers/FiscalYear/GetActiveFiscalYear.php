<?php

namespace Modules\Finance\Http\Controllers\FiscalYear;

use Modules\Finance\Http\Resources\FiscalYear\DocumentCollection;
use Modules\Finance\Models\FiscalYear\AccountingFiscalYear;
use Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Shared\Services\Responder;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     path="/api/workshops/accounting/fiscal-years/active/get",
 *     operationId="getActiveFiscalYear",
 *     tags={"Accounting > FiscalYears"},
 *     summary="Get active fiscal year",
 *     description="Returns active fiscal year for the tenant",
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
class GetActiveFiscalYear extends BaseCrudHandler
{
    public function handle(array $attributes = [])
    {
        $fiscalYear = AccountingFiscalYear::ForTenant($this->tenant->id)->where('status', AccountingFiscalYear::STATUS_ACTIVE)->first();

        if (is_null($fiscalYear)) {
            return Responder::error('سال مالی پیشفرض انتخاب نشده است؛ ابتدا سال مالی پیشفرض برای سیستم انتخاب کنید');
        }

        return Responder::success([
            'fiscal_year' => $fiscalYear,
        ]);
    }

    public function authorize()
    {
        return true;
    }
}
