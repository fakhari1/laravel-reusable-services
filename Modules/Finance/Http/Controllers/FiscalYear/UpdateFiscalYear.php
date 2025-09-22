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
 * @OA\Put(
 *     path="/api/workshops/accounting/fiscal-years/{id}/update",
 *     operationId="updateFiscalYear",
 *     tags={"Accounting > FiscalYears"},
 *     summary="Update a fiscal year",
 *     description="Update a fiscal year",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"title", "started_at", "finished_at"},
 *             @OA\Property(property="title", type="string", example="سال مالی 1403-1404", description="Fiscal year title"),
 *             @OA\Property(property="started_at", type="string", example="1404/01/01"),
 *             @OA\Property(property="finished_at", type="string", example="1404/12/29"),
 *             @OA\Property(property="status", type="string", enum={"active", "inactive", "closed"}, example="active", description="Fiscal year status"),
 *             @OA\Property(property="description", type="string", example="سال مالی اصلی شرکت", nullable=true, description="Optional description"),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Fiscal year created successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="success"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="error"),
 *             @OA\Property(property="message", type="string", example="The given data was invalid."),
 *             @OA\Property(property="errors", type="object")
 *         )
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="Forbidden - Insufficient permissions"
 *     )
 * )
 */
class UpdateFiscalYear extends BaseCrudHandler
{
    public function execute(array $attributes = [])
    {
//        $this->checkForOverlappingYears($attributes['started_at'], $attributes['finished_at']);

        $fiscalYear = AccountingFiscalYear::ForTenant($this->tenant?->id)->findOrFail($attributes['id']);

        $attributes['started_at'] = GlobalHelpers::farsiToEnglishNumbers($attributes['started_at']);
        $attributes['finished_at'] = GlobalHelpers::farsiToEnglishNumbers($attributes['finished_at']);

        $fiscalYear->update([
            'title' => $attributes['title'],
            'started_at' => DateTimeHelpers::jalaliDateToGregorian($attributes['started_at']),
            'finished_at' => DateTimeHelpers::jalaliDateToGregorian($attributes['finished_at']),
            'status' => $attributes['status'] ?? AccountingFiscalYear::STATUS_ACTIVE,
            'description' => $attributes['description'] ?? null,
        ]);

        if ($fiscalYear->status === AccountingFiscalYear::STATUS_ACTIVE) {
            $this->deactivateOtherFiscalYears($fiscalYear->id);
        }

        return Responder::success([
            'fiscal_year' => new DocumentResource($fiscalYear),
        ]);
    }

    private function checkForOverlappingYears($startDate, $endDate)
    {
        return AccountingFiscalYear::where('tenant_id', $this->tenant->id)
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('started_at', [$startDate, $endDate])
                    ->orWhereBetween('finished_at', [$startDate, $endDate])
                    ->orWhere(function ($q) use ($startDate, $endDate) {
                        $q->where('started_at', '<=', $startDate)
                            ->where('finished_at', '>=', $endDate);
                    });
            })
            ->exists();
    }

    public function validate()
    {
        return [
            'title' => ['required', Rule::unique('accounting_fiscal_years', 'title')
                ->where(function ($query) {
                    return $query->where('tenant_id', $this->tenant?->id);
                })->ignore($this->request->id)],
            'started_at' => ['required'],
            'finished_at' => ['required'],
            'status' => ['nullable', 'string', Rule::in(AccountingFiscalYear::$statuses)],
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
