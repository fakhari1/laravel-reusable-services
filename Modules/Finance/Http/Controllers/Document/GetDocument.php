<?php

namespace Modules\Finance\Http\Controllers\Document;

use Modules\Finance\Http\Resources\Document\DocumentResource;
use Modules\Finance\Models\Document\AccountingDocument;
use Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Shared\Services\Responder;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     path="/api/workshops/accounting/documents/1/get",
 *     operationId="getDocument",
 *     tags={"Accounting > Documents"},
 *     summary="Get document and information data",
 *     description="Returns document and information data for the tenant",
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
class GetDocument extends BaseCrudHandler
{
    public function handle(array $attributes = [])
    {
        $document = AccountingDocument::ForTenant($this->tenant->id)->findOrFail($attributes['id']);

        return Responder::success([
            'document' => new DocumentResource($document)
        ]);
    }

    public function authorize()
    {
        return true;
    }
}
