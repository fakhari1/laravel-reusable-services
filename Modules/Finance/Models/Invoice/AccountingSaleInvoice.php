<?php

namespace Modules\Finance\Models\Invoice;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Central\Models\Traits\HasTenant;
use Modules\Finance\Models\Invoice\Traits\SaleInvoice\SaleConstants;

class AccountingSaleInvoice extends Model
{
    use SoftDeletes, HasTenant, SaleConstants;

    protected $fillable = [
        'tenant_id',
        'type',
        'customer_id',
        'subjectable',
        'status',
        'delivery_at',
        'deliverer_id',
        'confirmed_at',
        'confirmed_by',
        'description',
        'deleted_at'
    ];


}
