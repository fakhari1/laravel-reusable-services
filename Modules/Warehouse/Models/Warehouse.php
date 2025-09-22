<?php

namespace Modules\Warehouse\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Finance\Models\Account\AccountingDetailedAccount;
use Modules\Identity\Models\Address;
use Modules\Identity\Models\TenantStaff;
use Modules\Tenancy\Models\Tenant;


class Warehouse extends Model
{
    protected $appends = [
        'storekeeper_label'
    ];

    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';

    public static array $statuses = [
        self::STATUS_ACTIVE,
        self::STATUS_INACTIVE
    ];

    protected $fillable = [
        'tenant_id',
        'name',
        'code',
        'type',
        'storekeeper_id',
        'address_id',
        'account_id',
        'status',
        'description'
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class, 'address_id');
    }

    public function racks()
    {
        return $this->hasMany(WarehouseRack::class, 'warehouse_id');
    }

    public function storekeeper()
    {
        return $this->belongsTo(TenantStaff::class, 'storekeeper_id');
    }

    public function account()
    {
        return $this->belongsTo(AccountingDetailedAccount::class, 'account_id');
    }

    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    public function getStorekeeperLabelAttribute()
    {
        $storekeeper = $this->storekeeper;
        $result = $storekeeper->full_name . '-' . $storekeeper->mobile;
        return $result;
    }

    public function warehouseProducts()
    {
        return $this->hasMany(WarehouseProduct::class, 'warehouse_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'warehouse_has_products', 'warehouse_id', 'product_id')
            ->withPivot([
                'rack_id',
                'quantity'
            ])
            ->withTimestamps();
    }

    public function documents()
    {
        return $this->hasMany(WarehouseDocument::class);
    }
}
