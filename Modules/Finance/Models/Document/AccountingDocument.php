<?php

namespace Modules\Finance\Models\Document;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Central\Models\Traits\HasTenant;
use Modules\Finance\Models\Document\Traits\Document\DocumentConstants;
use Modules\Finance\Models\Document\Traits\Document\DocumentGetters;
use Modules\Finance\Models\Document\Traits\Document\DocumentRelations;
use Modules\Finance\Models\Document\Traits\Document\DocumentSetters;
use Modules\Finance\Models\FiscalYear\AccountingFiscalYear;
use Modules\Identity\Models\TenantStaff;
use Modules\Tenancy\Models\Tenant;

class AccountingDocument extends Model
{
    use SoftDeletes, HasTenant, DocumentGetters, DocumentConstants, DocumentSetters, DocumentRelations;


    protected $fillable = [
        'tenant_id',
        'fiscal_year_id',
        'code',
        'date',
        'creator_id',
        'total_debit_amount',
        'total_credit_amount',
        'subjectable_type',
        'subjectable_id',
        'status',
        'description',
        'deleted_at'
    ];


}
