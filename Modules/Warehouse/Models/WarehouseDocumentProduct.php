<?php

namespace Modules\Modules\Warehouse\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseDocumentProduct extends Model
{
    protected $fillable = [
        'warehouse_document_id',
        'rack_id',
        'product_id',
        'unit',
        'count',
    ];

    public function warehouseDocument()
    {
        return $this->belongsTo(WarehouseDocument::class);
    }

    public function rack()
    {
        return $this->belongsTo(Rack::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }


}
