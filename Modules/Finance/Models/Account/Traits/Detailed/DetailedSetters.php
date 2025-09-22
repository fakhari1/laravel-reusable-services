<?php

namespace Modules\Finance\Models\Account\Traits\Detailed;

use Modules\Finance\Models\Document\AccountingDocumentArticle;

trait DetailedSetters
{
    public function calculateDebitAndCreditAmountFromAllArticles()
    {
        $totalDebitAmount = 0;
        $totalCreditAmount = 0;

        AccountingDocumentArticle::where('detailed_account_id', $this->id)->each(function ($article) use (&$totalCreditAmount, &$totalDebitAmount) {
            $totalCreditAmount += $article->credit_amount;
            $totalDebitAmount += $article->debit_amount;
        });

        $this->update([
            'debit_amount' => $totalDebitAmount,
            'credit_amount' => $totalCreditAmount
        ]);
    }

    public function increaseDebitAndCreditAmount($debitAmount, $creditAmount)
    {
        $this->update([
            'debit_amount' => $this->debit_amount + $debitAmount,
            'credit_amount' => $this->credit_amount + $creditAmount
        ]);
    }

    public function decreaseDebitAndCreditAmount($debitAmount, $creditAmount)
    {
        $this->update([
            'debit_amount' => $this->debit_amount - $debitAmount,
            'credit_amount' => $this->credit_amount - $creditAmount
        ]);
    }

    public function recalculateDebitAndCreditAmount($oldDebitAmount, $oldCreditAmount, $newDebitAmount, $newCreditAmount)
    {
        $totalDebitAmount = ($this->debit_amount - $oldDebitAmount) + $newDebitAmount;
        $totalCreditAmount = ($this->credit_amount - $oldCreditAmount) + $newCreditAmount;

        $this->update([
            'debit_amount' => $totalDebitAmount,
            'credit_amount' => $totalCreditAmount
        ]);
    }
}
