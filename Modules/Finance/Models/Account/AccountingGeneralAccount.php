<?php

namespace Modules\Finance\Models\Account;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Central\Models\Traits\HasTenant;
use Modules\Finance\Models\Account\Traits\General\GeneralConstants;
use Modules\Finance\Models\Account\Traits\General\GeneralGetters;
use Modules\Finance\Models\Account\Traits\General\GeneralRelations;
use Modules\Finance\Models\Account\Traits\General\GeneralScopes;
use Modules\Finance\Models\Account\Traits\General\GeneralSetters;

class AccountingGeneralAccount extends Model
{
    use SoftDeletes, HasTenant, GeneralConstants, GeneralGetters, GeneralRelations, GeneralScopes, GeneralSetters;

    protected $fillable = [
        'tenant_id',
        'group_id',
        'code',
        'title',
        'slug',
        'nature',
        'status',
        'description',
        'deleted_at'
    ];

    protected $appends = [
        'coding'
    ];
}
