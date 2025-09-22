<?php

namespace Modules\Finance\Models\Account;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Central\Models\Traits\HasTenant;
use Modules\Finance\Models\Account\AccountingDetailedAccount;
use Modules\Finance\Models\Account\AccountingGeneralAccount;
use Modules\Finance\Models\Account\Traits\Specific\SpecificConstants;
use Modules\Finance\Models\Account\Traits\Specific\SpecificGetters;
use Modules\Finance\Models\Account\Traits\Specific\SpecificRelations;

class AccountingSpecificAccount extends Model
{
    use SoftDeletes, HasTenant, SpecificConstants, SpecificGetters, SpecificRelations;


    protected $appends = [
        'nature',
        'coding'
    ];

    protected $fillable = [
        'tenant_id',
        'code',
        'title',
        'slug',
        'general_account_id',
        'status',
        'description',
        'deleted_at'
    ];

}
