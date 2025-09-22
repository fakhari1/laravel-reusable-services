<?php

namespace Modules\Finance\Models\Account;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Central\Models\Traits\HasTenant;
use Modules\Finance\Models\Account\Traits\General\GeneralScopes;
use Modules\Finance\Models\Account\Traits\Group\GroupConstants;
use Modules\Finance\Models\Account\Traits\Group\GroupGetters;
use Modules\Finance\Models\Account\Traits\Group\GroupRelations;

class AccountingAccountGroup extends Model
{
    use SoftDeletes, HasTenant, GroupConstants, GroupGetters, GroupRelations, GeneralScopes;

    protected $fillable = [
        'tenant_id',
        'title',
        'nature',
        'type'
    ];

}
