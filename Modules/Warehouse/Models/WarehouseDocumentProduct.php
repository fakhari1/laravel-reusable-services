<?php

namespace Modules\Warehouse\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseDocumentProduct extends Model
{
    protected $table = 'warehouse_document_has_products';

    protected $fillable = [
        'warehouse_document_id',
        'rack_id',
        'product_id',
        'unit',
        'count',
    ];

    public function warehouseDocument()
    {
        return $this->belongsTo(WarehouseDocument::class, 'warehouse_document_id');
    }

    public function rack()
    {
        return $this->belongsTo(WarehouseRack::class, 'rack_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
