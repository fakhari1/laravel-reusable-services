<?php

namespace Modules\Finance\Http\Controllers\Article;

use Illuminate\Validation\Rule;
use Modules\Finance\Models\Account\AccountingDetailedAccount;
use Modules\Finance\Models\Document\AccountingDocumentArticle;
use Modules\Shared\Http\Controllers\AsStaticRunner;
use Modules\Shared\Http\Controllers\BaseCrudHandler;

class StoreDocumentArticle extends BaseCrudHandler
{
    use AsStaticRunner;

    public function execute(array $attributes = [])
    {
        $detailedAccount = AccountingDetailedAccount::ForTenant($this->tenant?->id)->findOrFail($attributes['detailed_account_id']);

        $article = AccountingDocumentArticle::create([
            'tenant_id' => $this->tenant?->id,
            'document_id' => $attributes['document_id'],
            'detailed_account_id' => $attributes['detailed_account_id'],
            'description' => $attributes['description'],
            'debit_amount' => $attributes['debit_amount'],
            'credit_amount' => $attributes['credit_amount'],
        ]);

        $detailedAccount->increaseDebitAndCreditAmount(
            $article['debit_amount'],
            $article['credit_amount']
        );

        return $article;
    }

    public function authorize()
    {
        return true;
    }

    public function validate()
    {
        return [
            'document_id' => ['required'],
            'detailed_account_id' => ['required', Rule::exists('accounting_detailed_accounts', 'id')->where(function ($query) {
                $query->where('tenant_id', $this->tenant?->id);
            })],
            'description' => ['required'],
            'debit_amount' => ['required', 'numeric'],
            'credit_amount' => ['required', 'numeric'],
        ];
    }
}
