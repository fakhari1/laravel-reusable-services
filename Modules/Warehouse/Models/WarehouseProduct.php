<?php

namespace Modules\Warehouse\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Tenancy\Models\Tenant;

class WarehouseProduct extends Model
{
    use SoftDeletes;

    protected $table = 'warehouse_has_products';

    protected $fillable = [
        'tenant_id',
        'product_id',
        'warehouse_id',
        'rack_id',
        'quantity',
        'deleted_at'
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function rack()
    {
        return $this->belongsTo(WarehouseRack::class, 'rack_id');
    }

    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    public function computeQuantity($count, $unit, $type)
    {
        $product = $this->product;

        if ($product->main_counting_unit == $unit) {
            if ($type == WarehouseDocument::TYPE_RECEIPT) {
                $this->update([
                    'quantity' => $this->quantity + $count,
                ]);
            } else {
                $this->update([
                    'quantity' => $this->quantity - $count,
                ]);
            }
        } else {
            // ceil 1.43435345 to 1.44 not 2 integer
            $quantity = ceil($count / $product->coefficient * (10 ** 2)) / (10 ** 2);

            if ($type == WarehouseDocument::TYPE_RECEIPT) {
                $this->update([
                    'quantity' => $this->quantity + $quantity,
                ]);
            } else {
                $this->update([
                    'quantity' => $this->quantity - $quantity,
                ]);
            }
        }
    }
}
