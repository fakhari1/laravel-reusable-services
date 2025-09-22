<?php

namespace Modules\Finance\Models\Account\Traits\Specific;

use Modules\Finance\Models\Account\AccountingDetailedAccount;
use Modules\Finance\Models\Account\AccountingGeneralAccount;

trait SpecificRelations
{

    public function generalAccount()
    {
        return $this->belongsTo(AccountingGeneralAccount::class, 'general_account_id');
    }

    public function detailedAccounts()
    {
        return $this->hasMany(AccountingDetailedAccount::class, 'specific_account_id');
    }

    public function nestedDetailedAccounts()
    {
        return $this->hasMany(AccountingDetailedAccount::class, 'specific_account_id')->with('allChildren');
    }
}
