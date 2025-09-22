<?php

namespace Modules\Finance\Models\Invoice\Traits\Invoice;

use Modules\File\Models\File;
use Modules\Finance\Models\Document\AccountingDocument;
use Modules\Finance\Models\FiscalYear\AccountingFiscalYear;
use Modules\Finance\Models\Invoice\AccountingInvoiceItem;
use Modules\Identity\Models\TenantStaff;

trait InvoiceRelations
{
    public function creator()
    {
        return $this->belongsTo(TenantStaff::class, 'creator_id');
    }

    public function accountingDocument()
    {
        return $this->belongsTo(AccountingDocument::class);
    }

    public function invoiceable()
    {
        return $this->morphTo();
    }

    public function fiscalYear()
    {
        return $this->belongsTo(AccountingFiscalYear::class);
    }

    public function file()
    {
        return $this->belongsTo(File::class);
    }

    public function items()
    {
        return $this->hasMany(AccountingInvoiceItem::class);
    }
}
