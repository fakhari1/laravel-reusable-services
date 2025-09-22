<?php

namespace Modules\Finance\Models\Invoice;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Central\Models\Traits\HasTenant;
use Modules\Finance\Models\Invoice\Traits\InvoiceItem\InvoiceItemConstants;
use Modules\Finance\Models\Invoice\Traits\InvoiceItem\InvoiceItemGetters;
use Modules\Finance\Models\Invoice\Traits\InvoiceItem\InvoiceItemRelations;

class AccountingInvoiceItem extends Model
{
    use SoftDeletes, HasTenant, InvoiceItemConstants, InvoiceItemGetters, InvoiceItemRelations;

    protected $fillable = [
        'tenant_id',
        'invoice_id',
        'invoice_itemable_id',
        'invoice_itemable_type',
        'unit',
        'unit_price',
        'total_price',
        'discount_amount',
        'discount_percentage',
        'tax_percentage',
        'tax_amount',
        'additional_costs',
        'deleted_at'
    ];
}
