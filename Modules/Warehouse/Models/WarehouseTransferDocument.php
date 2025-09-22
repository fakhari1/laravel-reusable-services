<?php

namespace Modules\Warehouse\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Identity\Models\TenantCustomer;
use Modules\Identity\Models\TenantStaff;

class WarehouseTransferDocument extends Model
{
    protected $fillable = [
        'warehouse_id',
        'deliverer_id',
        'receiver_id',
    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function deliverer()
    {
        return $this->belongsTo(TenantStaff::class, 'deliverer_id');
    }

    public function receiver()
    {
        return $this->belongsTo(TenantCustomer::class, 'receiver_id');
    }

    public function warehouseDocument()
    {
        return $this->morphOne(WarehouseDocument::class, 'documentable');
    }
}
