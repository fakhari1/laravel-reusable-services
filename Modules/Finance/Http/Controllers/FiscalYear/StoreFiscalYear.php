<?php

namespace Modules\Finance\Http\Controllers\FiscalYear;

use Illuminate\Validation\Rule;
use Modules\Finance\Http\Resources\FiscalYear\DocumentResource;
use Modules\Finance\Models\FiscalYear\AccountingFiscalYear;
use Modules\Shared\Helpers\DateTimeHelpers;
use Modules\Shared\Helpers\GlobalHelpers;
use Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Shared\Services\Responder;
use OpenApi\Annotations as OA;

/**
 * @OA\Post(
 *     path="/api/workshops/accounting/fiscal-years/store",
 *     operationId="storeFiscalYear",
 *     tags={"Accounting > FiscalYears"},
 *     summary="Create new fiscal year",
 *     description="Creates a new fiscal year and returns the created data",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"title","started_at","finished_at"},
 *             @OA\Property(property="title", type="string", example="Fiscal Year 2024"),
 *             @OA\Property(property="started_at", type="string", example="1404/01/01"),
 *             @OA\Property(property="finished_at", type="string", example="1404/12/29"),
 *             @OA\Property(property="status", type="string", example="active"),
 *             @OA\Property(property="description", type="string", example="Main fiscal year for 2024", nullable=true)
 *         ),
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Fiscal year created successfully",
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="error"),
 *             @OA\Property(property="message", type="string", example="The given data was invalid."),
 *         )
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="Forbidden - Insufficient permissions"
 *     )
 * )
 */
class StoreFiscalYear extends BaseCrudHandler
{
    public function execute(array $attributes = [])
    {
        $attributes['started_at'] = GlobalHelpers::farsiToEnglishNumbers($attributes['started_at']);
        $attributes['finished_at'] = GlobalHelpers::farsiToEnglishNumbers($attributes['finished_at']);


        $fiscalYear = AccountingFiscalYear::create([
            'tenant_id' => $this->tenant?->id,
            'title' => $attributes['title'],
            'started_at' => DateTimeHelpers::jalaliDateToGregorian($attributes['started_at']),
            'finished_at' => DateTimeHelpers::jalaliDateToGregorian($attributes['finished_at']),
            'status' => $attributes['status'],
            'description' => $attributes['description'] ?? null,
        ]);

        if ($fiscalYear->status === AccountingFiscalYear::STATUS_ACTIVE) {
            $this->deactivateOtherFiscalYears($fiscalYear->id);
        }

        return Responder::success([
            'fiscal_year' => new DocumentResource($fiscalYear),
        ]);
    }
    public function validate()
    {
        return [
            'title' => ['required', Rule::unique('accounting_fiscal_years', 'title')->where(function ($query) {
                $query->where('tenant_id', $this->tenant?->id);
            })],
            'started_at' => ['required'],
            'finished_at' => ['required'],
            'status' => ['required', 'string', Rule::in(AccountingFiscalYear::$statuses)],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function authorize()
    {
        return true;
    }

    private function deactivateOtherFiscalYears($excludeId)
    {
        AccountingFiscalYear::where('tenant_id', $this->tenant->id)
            ->where('id', '!=', $excludeId)
            ->where('status', AccountingFiscalYear::STATUS_ACTIVE)
            ->update(['status' => AccountingFiscalYear::STATUS_INACTIVE]);
    }
}
