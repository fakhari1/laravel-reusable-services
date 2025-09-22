<?php

namespace Modules\Finance\Http\Controllers\FiscalYear;

use Modules\Finance\Http\Resources\FiscalYear\FiscalYearCollection;
use Modules\Finance\Models\FiscalYear\AccountingFiscalYear;
use Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Shared\Services\Responder;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     path="/api/workshops/accounting/fiscal-years/all",
 *     operationId="getFiscalYearsList",
 *     tags={"Accounting > FiscalYears"},
 *     summary="Get list of fiscal years",
 *     description="Returns list of fiscal years for the tenant",
 *     @OA\Parameter(
 *         name="page",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="integer"),
 *         description="Page number"
 *     ),
 *     @OA\Parameter(
 *         name="per_page",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="integer"),
 *         description="Items per page"
 *     ),
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
class GetAllFiscalYearsList extends BaseCrudHandler
{
    public function handle(array $attributes = [])
    {
        $tenantId = $this->tenant?->id;

        $query = AccountingFiscalYear::ForTenant($tenantId);

        $fiscalYears = $query->paginate(20);

        return Responder::success(new FiscalYearCollection($fiscalYears));
    }
    public function authorize()
    {
        return true;
    }
}
