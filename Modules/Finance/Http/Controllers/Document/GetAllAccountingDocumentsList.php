<?php

namespace Modules\Finance\Http\Controllers\Document;

use Modules\Finance\Http\Resources\Document\DocumentCollection;
use Modules\Finance\Http\Resources\Document\DocumentResource;
use Modules\Finance\Models\Document\AccountingDocument;
use Modules\Shared\Helpers\DateTimeHelpers;
use Modules\Shared\Helpers\GlobalHelpers;
use Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Shared\Services\Responder;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     path="/api/workshops/accounting/documents/all",
 *     operationId="getAllDocuments List",
 *     tags={"Accounting > Documents"},
 *     summary="Get all documents and their information data",
 *     description="Returns all documents and information data for the tenant",
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
class GetAllAccountingDocumentsList extends BaseCrudHandler
{
    public function handle(array $attributes = [])
    {
        $documents = AccountingDocument::ForTenant($this->tenant->id);

        if (isset($attributes['started_at'])) {
            $attributes['started_at'] = DateTimeHelpers::jalaliDateToGregorian(GlobalHelpers::farsiToEnglishNumbers($attributes['started_at']));
            $documents->where('date', '>=', $attributes['started_at']);
        }

        if (isset($attributes['finished_at'])) {
            $attributes['finished_at'] = DateTimeHelpers::jalaliDateToGregorian(GlobalHelpers::farsiToEnglishNumbers($attributes['finished_at']));
            $documents->where('date', '>=', $attributes['finished_at']);
        }

        $documents = $documents->get();

        return Responder::success(new DocumentCollection($documents));
    }

    public function authorize()
    {
        return true;
    }
}
