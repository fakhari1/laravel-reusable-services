<?php

namespace Modules\Finance\Models\Invoice;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Central\Models\Traits\HasTenant;
use Modules\File\Models\File;
use Modules\Finance\Models\Document\AccountingDocument;
use Modules\Finance\Models\FiscalYear\AccountingFiscalYear;
use Modules\Finance\Models\Invoice\Traits\Invoice\InvoiceConstants;
use Modules\Finance\Models\Invoice\Traits\Invoice\InvoiceGetters;
use Modules\Finance\Models\Invoice\Traits\Invoice\InvoiceRelations;
use Modules\Identity\Models\TenantStaff;
use Modules\Tenancy\Models\Tenant;

class AccountingInvoice extends Model
{
    use SoftDeletes, HasTenant, InvoiceConstants, InvoiceGetters, InvoiceRelations;

    protected $fillable = [
        'tenant_id',
        'fiscal_year_id',
        'creator_id',
        'document_id',
        'number',
        'date',
        'subtotal_amount',
        'discount_amount',
        'discount_percentage',
        'tax_amount',
        'tax_percentage',
        'total_amount',
        'paid_amount',
        'remaining_amount',
        'invoiceable_id',
        'invoiceable_type',
        'type',
        'file_id',
        'status',
        'meta_data',
        'description',
        'deleted_at'
    ];

    protected $casts = [
        'meta_data' => 'array',
    ];
}
