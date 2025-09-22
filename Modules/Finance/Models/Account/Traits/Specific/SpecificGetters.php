<?php

namespace Modules\Finance\Models\Account\Traits\Specific;

trait SpecificGetters
{
    public static function getTranslatedStatuses()
    {
        $result = [];

        foreach (self::$statuses as $key => $status) {
            $result[] = [
                'key' => $status,
                'value' => $status
            ];
        }

        return $result;
    }

    public function getNatureAttribute()
    {
        return $this->generalAccount?->nature;
    }

    public function getCodingAttribute()
    {
        return "{$this->generalAccount?->code}{$this->code}";
    }

    public function getRemainderAmount()
    {
        return $this->getTotalDebitAmount() - $this->getTotalCreditAmount();
    }
    public function getTotalDebitAmount()
    {
        $totalDebitAmount = 0;

        $this->detailedAccounts()->each(function ($account) use (&$totalDebitAmount) {
            $totalDebitAmount += $account->getTotalDebitAmount();
        });

        return $totalDebitAmount;
    }

    public function getTotalCreditAmount()
    {
        $totalCreditAmount = 0;

        $this->detailedAccounts()->each(function ($account) use (&$totalCreditAmount) {
            $totalCreditAmount += $account->getTotalCreditAmount();
        });

        return $totalCreditAmount;
    }
}
