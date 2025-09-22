<?php

namespace Modules\Finance\Models\Account\Traits\Detailed;

trait DetailedGetters
{
    public function getRemainderAmount()
    {
        return $this->getTotalDebitAmount() - $this->getTotalCreditAmount();
    }

    public function getTotalDebitAmount()
    {
        $totalDebitAmount = 0;

        if (!is_null($this->children)) {
            $this->children()->each(function ($account) use (&$totalDebitAmount) {
                $totalDebitAmount += $account->getTotalDebitAmount();
            });
        } else {
            $totalDebitAmount += $this->debit_amount;
        }

        return $totalDebitAmount;
    }

    public function getTotalCreditAmount()
    {
        $totalCreditAmount = 0;
        if (!is_null($this->children)) {
            $this->children()->each(function ($account) use (&$totalCreditAmount) {
                $totalCreditAmount += $account->getTotalCreditAmount();
            });
        } else {
            $totalCreditAmount += $this->credit_amount;
        }

        return $totalCreditAmount;
    }

    public static function getTranslatedStatuses()
    {
        $result = [];

        foreach (self::$statuses as $key => $status) {
            $result [] = [
                'key' => $status,
                'value' => $status
            ];
        }

        return $result;
    }

    public function getNatureAttribute()
    {
        return $this->specificAccount?->nature;
    }

    public function getCodingAttribute()
    {
        $coding = '';
        if (!is_null($this->specificAccount)) {
            $coding .= $this->specificAccount->coding;
        }

        if (!is_null($this->parent)) {
            $coding .= $this->parent->coding;
        }

        return "{$coding}{$this->code}";
    }

    public function getSubjectAttribute()
    {
        if (is_null($this->subjectable_type) && is_null($this->subjectable_id)) {
            return self::SUBJECT_MANUAL;
        }

        if ($this->subjectable_type == self::SUBJECT_TYPE_INVOICE) {
            return self::SUBJECT_INVOICE;
        }

        if ($this->subjectable_type == self::SUBJECT_TYPE_TRANSACTION) {
            return self::SUBJECT_TRANSACTION;
        }
    }
}
