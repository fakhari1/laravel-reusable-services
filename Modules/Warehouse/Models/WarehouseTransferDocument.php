<?php

namespace Modules\Modules\Warehouse\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Identity\Models\TenantStaff;

class WarehouseTransferDocument extends Model
{
    protected $fillable = [
        'warehouse_id',
        'deliverer_id',
        'receiver',
        'status',
        'description',
    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function deliverer()
    {
        return $this->belongsTo(TenantStaff::class, 'deliverer_id');
    }

    public function warehouseDocument()
    {
        return $this->morphOne(WarehouseDocument::class, 'documentable');
    }
}
