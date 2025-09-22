<?php

namespace Modules\Finance\Models\Document\Traits\Document;

use Modules\Finance\Models\Document\AccountingDocumentArticle;
use Modules\Finance\Models\FiscalYear\AccountingFiscalYear;
use Modules\Identity\Models\TenantStaff;

trait DocumentRelations
{
    public function fiscalYear()
    {
        return $this->belongsTo(AccountingFiscalYear::class);
    }

    public function creator()
    {
        return $this->belongsTo(TenantStaff::class);
    }

    public function articles()
    {
        return $this->hasMany(AccountingDocumentArticle::class, 'document_id');
    }
}
