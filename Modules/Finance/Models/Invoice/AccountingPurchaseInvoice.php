<?php

namespace Modules\Finance\Models\Invoice;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Central\Models\Traits\HasTenant;
use Modules\Finance\Models\Invoice\Traits\PurchaseInvoice\PurchaseConstants;

class AccountingPurchaseInvoice extends Model
{
    use SoftDeletes, HasTenant, PurchaseConstants;

    protected $fillable = [];


}
