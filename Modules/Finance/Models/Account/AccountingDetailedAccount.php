<?php

namespace Modules\Finance\Models\Account;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Central\Models\Traits\HasTenant;
use Modules\Finance\Models\Account\Traits\Detailed\DetailedConstants;
use Modules\Finance\Models\Account\Traits\Detailed\DetailedGetters;
use Modules\Finance\Models\Account\Traits\Detailed\DetailedRelations;
use Modules\Finance\Models\Account\Traits\Detailed\DetailedSetters;
use Modules\Finance\Models\Account\AccountingSpecificAccount;
use Modules\Finance\Models\Document\AccountingDocumentArticle;
use Modules\Tenancy\Models\Tenant;

class AccountingDetailedAccount extends Model
{
    use SoftDeletes, HasTenant, DetailedGetters, DetailedConstants, DetailedRelations, DetailedSetters;

    protected $appends = [
        'coding',
        'nature',
        'subject'
    ];

    protected $fillable = [
        'tenant_id',
        'code',
        'title',
        'slug',
        'specific_account_id',
        'parent_id',
        'debit_amount',
        'credit_amount',
        'level',
        'status',
        'description',
        'deleted_at'
    ];

}
