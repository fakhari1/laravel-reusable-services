<?php

namespace Modules\Finance\Http\Controllers\FiscalYear;

use Modules\Finance\Models\FiscalYear\AccountingFiscalYear;
use Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Shared\Services\Responder;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     path="/api/workshops/accounting/fiscal-years/get-create-update-data",
 *     operationId="getFiscalYearCreateUpdateData",
 *     tags={"Accounting > FiscalYears"},
 *     summary="Get fiscal year create update data",
 *     description="Get fiscal year and create data",
 *     @OA\Response(
 *         response=200,
 *         description="Data fetched successfully",
 *     ),
 * )
 */
class GetFiscalYearCreateUpdateData extends BaseCrudHandler
{
    public function execute(array $attributes = [])
    {
        return Responder::success([
            'statuses' => AccountingFiscalYear::getTranslatedStatuses(),
        ]);
    }
    public function authorize()
    {
        return true;
    }
}
