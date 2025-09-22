<?php

namespace Modules\Finance\Models\Account\Traits\Detailed;

use Modules\Finance\Models\Account\AccountingDetailedAccount;
use Modules\Finance\Models\Account\AccountingSpecificAccount;
use Modules\Finance\Models\Document\AccountingDocumentArticle;

trait DetailedRelations
{
    public function specificAccount()
    {
        return $this->belongsTo(AccountingSpecificAccount::class, 'specific_account_id');
    }

    public function parent()
    {
        return $this->belongsTo(AccountingDetailedAccount::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(AccountingDetailedAccount::class, 'parent_id');
    }

    public function allChildren()
    {
        return $this->hasMany(AccountingDetailedAccount::class, 'parent_id')->with('allChildren');
    }

    public function documentArticles()
    {
        return $this->hasMany(AccountingDocumentArticle::class, 'detailed_account_id');
    }

}
