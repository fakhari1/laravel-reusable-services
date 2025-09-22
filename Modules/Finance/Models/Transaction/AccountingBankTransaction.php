<?php

namespace Modules\Finance\Models\Transaction;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Central\Models\Traits\HasTenant;
use Modules\Finance\Models\Account\AccountingDetailedAccount;

class AccountingBankTransaction extends Model
{
    use SoftDeletes, HasTenant;

    protected $fillable = [
        'tenant_id',
        'type',
        'source_account_id',
        'destination_account_id',
        'source_account_number',
        'destination_account_number',
        'source_owner_data',
        'destination_owner_data',
        'meta_data',
        'deleted_at'
    ];

    public const TYPE_POS = 'pos';
    public const TYPE_TO_CARD = 'transfer_to_card';
    public const TYPE_TO_ACCOUNT = 'transfer_to_account';

    public static $types = [
        self::TYPE_POS,
        self::TYPE_TO_CARD,
        self::TYPE_TO_ACCOUNT
    ];

    public function sourceAccount()
    {
        return $this->belongsTo(AccountingDetailedAccount::class, 'source_account_id');
    }

    public function destinationAccount()
    {
        return $this->belongsTo(AccountingDetailedAccount::class, 'destination_account_id');
    }
}
