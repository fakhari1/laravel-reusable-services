<?php

namespace Modules\Finance\Http\Controllers\Document;

use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Modules\Finance\Http\Controllers\Article\StoreDocumentArticle;
use Modules\Finance\Http\Controllers\Article\UpdateDocumentArticle;
use Modules\Finance\Http\Resources\Document\DocumentResource;
use Modules\Finance\Models\Document\AccountingDocument;
use Modules\Finance\Models\Document\AccountingDocumentArticle;
use Modules\Shared\Helpers\DateTimeHelpers;
use Modules\Shared\Helpers\GlobalHelpers;
use Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Shared\Services\Responder;
use OpenApi\Annotations as OA;

/**
 * @OA\Put(
 *     path="/api/workshops/accounting/documents/1/update",
 *     summary="Update an exists document",
 *     operationId="updateDocument",
 *     tags={"Accounting > Documents"},
 *          @OA\RequestBody(
 *          required=true,
 *          @OA\JsonContent(
 *              required={"date","code","description","articles"},
 *              @OA\Property(property="date", type="string", example="1404/04/04"),
 *              @OA\Property(property="code", type="string", example="1001"),
 *              @OA\Property(property="description", type="string", example="Cash account description", nullable=true),
 *              @OA\Property(property="articles", type="array",
 *                   @OA\Items(type="object",
 *                       @OA\Property(property="detailed_account_id", type="integer",example="1"),
 *                       @OA\Property(property="description", type="string",example="this is test for description"),
 *                       @OA\Property(property="debit_amount", type="integer", example="10000"),
 *                       @OA\Property(property="credit_amount", type="integer", example="0")
 *                   )
 *              )
 *          ),
 *      ),
 *     @OA\Response(
 *         response=200,
 *         description="Document updated successfully"
 *     )
 * )
 */
class UpdateDocument extends BaseCrudHandler
{
    public function execute(array $attributes = [])
    {
        $attributes['date'] = GlobalHelpers::farsiToEnglishNumbers($attributes['date']);

        $document = AccountingDocument::ForTenant($this->tenant?->id)->findOrFail($attributes['id']);

        $document->update([
            'creator_id' => auth('api-tenant')->id(),
            'date' => DateTimeHelpers::jalaliDateToGregorian($attributes['date']),
            'code' => $attributes['code'],
        ]);

        $total_debit_amount = 0;
        $total_credit_amount = 0;

        $documentArticlesIds = $document->articles->pluck('id')->toArray();
        $requestArticlesIds = collect($attributes['articles'])
            ->pluck('id')
            ->filter()
            ->toArray();

        AccountingDocumentArticle::ForTenant($this->tenant->id)->whereIn('id', array_diff($documentArticlesIds, $requestArticlesIds))->delete();

        foreach ($attributes['articles'] as $key => $article) {
            if (isset($article['id']) && !is_null($article['id'])) {
                $existsArticle = UpdateDocumentArticle::run($article);

                $total_debit_amount += $article['debit_amount'];
                $total_credit_amount += $article['credit_amount'];
            } else {
                $createdArticle = StoreDocumentArticle::run([
                    'tenant_id' => $this->tenant->id,
                    'document_id' => $document->id,
                    'description' => $article['description'],
                    'detailed_account_id' => $article['detailed_account_id'],
                    'debit_amount' => $article['debit_amount'],
                    'credit_amount' => $article['credit_amount'],
                ]);


                $total_debit_amount += $createdArticle->debit_amount;
                $total_credit_amount += $createdArticle->credit_amount;
            }
        }

        $document->update([
            'total_debit_amount' => $total_debit_amount,
            'total_credit_amount' => $total_credit_amount,
            'date' => DateTimeHelpers::jalaliDateToGregorian($attributes['date']),
            'fixer_id' => auth('api-tenant')->id(),
            'code' => $attributes['code'],
        ]);

        return Responder::success([
            'document' => new DocumentResource($document)
        ]);
    }

    public function authorize()
    {
        return true;
    }

    public function validate()
    {
        return [
            'code' => ['required', Rule::unique('accounting_documents', 'code')->where(function ($query) {
                return $query->where('tenant_id', $this->tenant?->id);
            })->ignore($this->request->id)],
            'date' => ['required'],
            'description' => ['nullable'],
            'articles' => ['required', 'array'],
            'articles.*.id' => ['nullable'],
            'articles.*.detailed_account_id' => ['required', Rule::exists('accounting_detailed_accounts', 'id')->where(function ($query) {
                $query->where('tenant_id', $this->tenant?->id);
            })],
            'articles.*.description' => ['required'],
            'articles.*.debit_amount' => ['required', 'numeric'],
            'articles.*.credit_amount' => ['required', 'numeric'],
        ];
    }

    public function afterValidator(array $attributes)
    {
        foreach ($attributes['articles'] as $key => $article) {
            $number = $key + 1;

            if (
                ($article['debit_amount'] > 0 && $article['credit_amount'] > 0) ||
                ($article['debit_amount'] == 0 && $article['credit_amount'] == 0)
            ) {
                throw ValidationException::withMessages([
                    "برای آرتیکل {$number} بدهکار/بستانکار نامعتبر وارد شده است",
                ]);
            }
        }
    }
}
