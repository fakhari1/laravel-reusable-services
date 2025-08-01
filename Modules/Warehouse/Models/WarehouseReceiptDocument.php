<?php

namespace Modules\Modules\Warehouse\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Identity\Models\TenantStaff;

class WarehouseReceiptDocument extends Model
{
    protected $fillable = [
        'warehouse_id',
        'receiver_id',
        'deliverer',
        'status',
        'description',
        'issuance_date',
    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function receiver()
    {
        return $this->belongsTo(TenantStaff::class, 'receiver_id');
    }

    public function warehouseDocument()
    {
        return $this->morphOne(WarehouseDocument::class, 'documentable');
    }
}
