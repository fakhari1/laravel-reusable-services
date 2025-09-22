<?php

namespace Modules\Finance\Models\Account\Traits\General;

use Modules\Finance\Models\Account\AccountingAccountGroup;
use Modules\Finance\Models\Account\AccountingDetailedAccount;
use Modules\Finance\Models\Account\AccountingSpecificAccount;

trait GeneralRelations
{
    public function accountGroup()
    {
        return $this->belongsTo(AccountingAccountGroup::class, 'group_id');
    }

    public function specificAccounts()
    {
        return $this->hasMany(AccountingSpecificAccount::class, 'general_account_id');
    }

    public function detailedAccounts()
    {
        return $this->hasManyThrough(AccountingDetailedAccount::class, AccountingSpecificAccount::class);
    }
}
