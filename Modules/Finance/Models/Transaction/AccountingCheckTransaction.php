<?php

namespace Modules\Finance\Models\Transaction;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Central\Models\Traits\HasTenant;
use Modules\Finance\Models\Account\AccountingDetailedAccount;

class AccountingCheckTransaction extends Model
{
    use SoftDeletes, HasTenant;

    protected $fillable = [
        'tenant_id',
        'code',
        'bank_account_id',
        'issuance_at',
        'due_at',
        'number',
        'account_id',
        'serial_number',
        'ownerable_type',
        'ownerable_id',
        'owner_information',
        'description',
        'deleted_at'
    ];

    public function bankAccount()
    {
        return $this->belongsTo(AccountingDetailedAccount::class, 'bank_account_id');
    }

    public function sourceAccount()
    {
        return $this->belongsTo(AccountingDetailedAccount::class, 'source_account_id');
    }

    public function ownerable()
    {
        return $this->morphTo();
    }
}
