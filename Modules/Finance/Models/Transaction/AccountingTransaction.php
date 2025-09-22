<?php

namespace Modules\Finance\Models\Transaction;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Central\Models\Traits\HasTenant;
use Modules\Finance\Models\Account\AccountingDetailedAccount;
use Modules\Finance\Models\Document\AccountingDocument;
use Modules\Finance\Models\FiscalYear\AccountingFiscalYear;
use Modules\Finance\Models\Invoice\AccountingInvoice;
use Modules\Identity\Models\TenantStaff;
use Modules\Tenancy\Models\Tenant;

class AccountingTransaction extends Model
{
    use SoftDeletes, HasTenant;
    protected $fillable = [
        'tenant_id',
        'transactionable_id',
        'transactionable_type',
        'number',
        'date',
        'type',
        'method',
        'party_id',
        'party_type',
        'amount',
        'fee',
        'discount',
        'tax',
        'total_amount',
        'fiscal_year_id',
        'accounting_document_id',
        'source_account_id',
        'destination_account_id',
        'invoice_id',
        'creator_id',
        'status',
        'meta_data',
        'description',
        'deleted_at'
    ];

    public const TYPE_RECEIPT = 'receipt';
    public const TYPE_PAYMENT = 'payment';

    public static array $types = [
        self::TYPE_RECEIPT,
        self::TYPE_PAYMENT,
    ];

    public const METHOD_BANK = 'bank';
    public const METHOD_CASH = 'cash';
    public const METHOD_CHECK = 'check';

    public static array $methods = [
        self::METHOD_BANK,
        self::METHOD_CASH,
        self::METHOD_CHECK,
    ];

    public const STATUS_DRAFT = 'draft';
    public const STATUS_PENDING = 'pending';
    public const STATUS_CLEARED = 'cleared';

    public static array $statuses = [
        self::STATUS_DRAFT,
        self::STATUS_PENDING,
        self::STATUS_CLEARED,
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function transanctionable()
    {
        return $this->morphTo();
    }

    public function party()
    {
        return $this->morphTo();
    }

    public function fiscalYear()
    {
        return $this->belongsTo(AccountingFiscalYear::class);
    }

    public function accountingDocument()
    {
        return $this->belongsTo(AccountingDocument::class);
    }

    public function sourceAccount()
    {
        return $this->belongsTo(AccountingDetailedAccount::class, 'source_account_id');
    }

    public function destinationAccount()
    {
        return $this->belongsTo(AccountingDetailedAccount::class, 'destination_account_id');
    }

    public function invoice()
    {
        return $this->belongsTo(AccountingInvoice::class);
    }

    public function creator()
    {
        return $this->belongsTo(TenantStaff::class, 'creator_id');
    }


}
