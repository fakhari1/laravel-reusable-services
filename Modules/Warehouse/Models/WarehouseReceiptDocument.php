<?php

namespace Modules\Warehouse\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Identity\Models\TenantCustomer;
use Modules\Identity\Models\TenantStaff;

class WarehouseReceiptDocument extends Model
{
    protected $fillable = [
        'warehouse_id',
        'receiver_id',
        'deliverer_id',
    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function receiver()
    {
        return $this->belongsTo(TenantStaff::class, 'receiver_id');
    }

    public function deliverer()
    {
        return $this->belongsTo(TenantCustomer::class, 'deliverer_id');
    }

    public function warehouseDocument()
    {
        return $this->morphOne(WarehouseDocument::class, 'documentable');
    }
}
