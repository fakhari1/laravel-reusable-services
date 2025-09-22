<?php

namespace Modules\Finance\Models\Account\Traits\General;

use Modules\Finance\Models\Account\AccountingDetailedAccount;

trait GeneralGetters
{
    public function getOwnDetailedAccounts()
    {
        $specificAccountsIds = $this->specificAccounts()->pluck('id')->toArray();

        return AccountingDetailedAccount::whereIn('specific_account_id', $specificAccountsIds)->get();
    }

    public function getRemainderAmount()
    {
        return $this->getTotalDebitAmount() - $this->getTotalCreditAmount();
    }

    public function getTotalDebitAmount()
    {
        $totalDebitAmount = 0;

        $this->specificAccounts()->each(function ($account) use (&$totalDebitAmount) {
            $totalDebitAmount += $account->getTotalDebitAmount();
        });

        return $totalDebitAmount;
    }

    public function getTotalCreditAmount()
    {
        $totalCreditAmount = 0;

        $this->specificAccounts()->each(function ($account) use (&$totalCreditAmount) {
            $totalCreditAmount += $account->getTotalCreditAmount();
        });

        return $totalCreditAmount;
    }

    public static function getTranslatedNatures()
    {
        $result = [];

        foreach (self::$natures as $key => $nature) {
            $result [] = [
                'key' => $nature,
                'value' => $nature
            ];
        }
        return $result;
    }

    public static function getTranslatedStatuses()
    {
        $result = [];

        foreach (self::$statuses as $key => $status) {
            $result [] = [
                'key' => $status,
                'value' => $status,
            ];
        }

        return $result;
    }

    public function getCodingAttribute()
    {
        return $this->code;
    }
}

