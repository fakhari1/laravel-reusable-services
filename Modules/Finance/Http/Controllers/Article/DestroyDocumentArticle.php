<?php

namespace Modules\Finance\Http\Controllers\Article;

use Illuminate\Validation\Rule;
use Modules\Finance\Models\Document\AccountingDocumentArticle;
use Modules\Shared\Http\Controllers\AsStaticRunner;
use Modules\Shared\Http\Controllers\BaseCrudHandler;

class DestroyDocumentArticle extends BaseCrudHandler
{
    use AsStaticRunner;

    public function execute(array $attributes = [])
    {
        $article = AccountingDocumentArticle::findOrFail($attributes['id']);

        $article->detailedAccount?->decreaseDebitAndCreditAmount(
            $article->debit_amount,
            $article->credit_amount
        );

        return $article->delete();
    }

    public function authorize()
    {
        return true;
    }

    public function validate()
    {
        return [
            'id' => ['required', Rule::exists('accounting_document_articles', 'id')->where(function ($query) {
                $query->where('tenant_id', $this->tenant?->id);
            })]
        ];
    }
}
