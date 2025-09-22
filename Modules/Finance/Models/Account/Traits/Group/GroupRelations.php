<?php

namespace Modules\Finance\Models\Account\Traits\Group;

use Modules\Finance\Models\Account\AccountingGeneralAccount;

trait GroupRelations
{
    public function generalAccounts()
    {
        return $this->hasMany(AccountingGeneralAccount::class, 'group_id');
    }
}
