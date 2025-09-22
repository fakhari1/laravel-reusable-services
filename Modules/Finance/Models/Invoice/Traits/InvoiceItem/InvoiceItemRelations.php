<?php

namespace Modules\Finance\Models\Invoice\Traits\InvoiceItem;

use Modules\Finance\Models\Invoice\AccountingInvoice;

trait InvoiceItemRelations
{
    public function invoice()
    {
        return $this->belongsTo(AccountingInvoice::class);
    }

    public function invoiceItemable()
    {
        return $this->morphTo();
    }
}
