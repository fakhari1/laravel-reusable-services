<?php

namespace Modules\Finance\Models\Document\Traits\Article;

use Modules\Finance\Models\Account\AccountingDetailedAccount;
use Modules\Finance\Models\Document\AccountingDocument;

trait ArticleRelations
{
    public function document()
    {
        return $this->belongsTo(AccountingDocument::class, 'document_id');
    }

    public function detailedAccount()
    {
        return $this->belongsTo(AccountingDetailedAccount::class, 'detailed_account_id');
    }
}
