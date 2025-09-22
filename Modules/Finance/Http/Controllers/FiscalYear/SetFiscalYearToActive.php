<?php

namespace Modules\Finance\Http\Controllers\FiscalYear;

use Illuminate\Validation\Rule;
use Modules\Finance\Models\FiscalYear\AccountingFiscalYear;
use Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Shared\Services\Responder;
use OpenApi\Annotations as OA;

/**
 * @OA\Post(
 *     path="/api/workshops/accounting/fiscal-years/active/set",
 *     operationId="setFiscalYearAsActive",
 *     tags={"Accounting > FiscalYears"},
 *     summary="Set fiscal year as active",
 *     description="Set fiscal year as active for the tenant",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"fiscal_year_id"},
 *             @OA\Property(property="fiscal_year_id", type="integer", example="1"),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Operation was successful",
 *     ),
 * )
 */

/**
 * @OA\Post(
 *     path="/api/workshops/accounting/fiscal-years/active/set",
 *     operationId="setDefaultFiscalYear",
 *     tags={"Accounting > FiscalYears"},
 *     summary="Set active fiscal years",
 *     description="Set active fiscal year for the tenant",
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
class SetFiscalYearToActive extends BaseCrudHandler
{
    public function execute(array $attributes = [])
    {
        AccountingFiscalYear::ForTenant($this->tenant?->id)->where('status' , '<>', AccountingFiscalYear::STATUS_CLOSED)->update([
            'status' => AccountingFiscalYear::STATUS_INACTIVE
        ]);

        AccountingFiscalYear::ForTenant($this->tenant->id)->where('id', $attributes['fiscal_year_id'])->update([
            'status' => AccountingFiscalYear::STATUS_ACTIVE,
        ]);

        return Responder::success('سال مالی با موفقیت تنظیم شد');
    }

    public function authorize()
    {
        return true;
    }

    public function validate()
    {
        return [
            'fiscal_year_id' => ['required', Rule::exists('accounting_fiscal_years', 'id')->where(function ($query) {
                $query->where('tenant_id', $this->tenant->id);
            })]
        ];
    }
}
