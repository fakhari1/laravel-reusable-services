<?php

namespace Modules\Finance\Http\Controllers\Document;

use Modules\Finance\Http\Controllers\Article\DestroyDocumentArticle;
use Modules\Finance\Models\Document\AccountingDocument;
use Modules\Shared\Http\Controllers\BaseCrudHandler;
use OpenApi\Annotations as OA;

/**
 * @OA\Delete(
 *     path="/api/workshops/accounting/documents/1/delete",
 *     summary="Delete an existing document",
 *     operationId="deleteDocument",
 *     tags={"Accounting > Documents"},
 *     @OA\Response(
 *         response=200,
 *         description="Document deleted successfully"
 *     )
 * )
 */
class DestroyDocument extends BaseCrudHandler
{
    public function execute(array $attributes = [])
    {
        $document = AccountingDocument::ForTenant($this->tenant?->id)->findOrFail($attributes['id']);

        $document->articles()->each(function ($article) {
            DestroyDocumentArticle::run([
                'id' => $article->id
            ]);
        });

        return $document->delete();
    }

    public function authorize()
    {
        return true;
    }
}
