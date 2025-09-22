<?php

namespace Modules\Warehouse\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Tenancy\Models\Tenant;
use OpenApi\Annotations as OA;

class Product extends Model
{
    protected $fillable = [
        'tenant_id',
        'dasterang_product_id',
        'code',
        'product_category_id',
        'name',
        'beginning_inventory',
        'main_counting_unit',
        'coefficient',
        'sub_counting_unit',
        'status',
        'thumbnail',
        'image',
        'type',
        'description',
        'deleted_at'
    ];

    protected $casts = [
        'type' => 'array',
    ];

    public const TYPE_RAW_MATERIALS = 'raw materials';
    public const TYPE_PRODUCT = 'product';
    public const TYPE_PACKAGING = 'packaging';
    public const TYPE_CONSUMABLE_ITEMS = 'consumable items';
    public const TYPE_WASTE = 'waste';
    public const TYPE_ASSETS = 'assets';
    public const TYPE_IN_PROGRESS = 'in progress';
    public const TYPE_SEMI_FINISHED = 'semi finished';

    public static array $types = [
        self::TYPE_RAW_MATERIALS,
        self::TYPE_PRODUCT,
        self::TYPE_PACKAGING,
        self::TYPE_CONSUMABLE_ITEMS,
        self::TYPE_WASTE,
        self::TYPE_ASSETS,
        self::TYPE_IN_PROGRESS,
        self::TYPE_SEMI_FINISHED,
    ];

    public const COUNTING_UNIT_PACKAGE = 'بسته';
    public const COUNTING_UNIT_BOX = 'جعبه';
    public const COUNTING_UNIT_METER = 'متر';
    public const COUNTING_UNIT_SQUARE_METER = 'متر مربع';
    public const COUNTING_UNIT_LITER = 'لیتر';
    public const COUNTING_UNIT_KILOGRAM = 'کیلوگرم';
    public const COUNTING_UNIT_GRAM = 'گرم';
    public const COUNTING_UNIT_COUNT = 'عدد';
    public const COUNTING_UNIT_PALLET = 'پالت';

    public static array $countingUnits = [
        self::COUNTING_UNIT_PACKAGE,
        self::COUNTING_UNIT_BOX,
        self::COUNTING_UNIT_METER,
        self::COUNTING_UNIT_SQUARE_METER,
        self::COUNTING_UNIT_LITER,
        self::COUNTING_UNIT_KILOGRAM,
        self::COUNTING_UNIT_GRAM,
        self::COUNTING_UNIT_COUNT,
        self::COUNTING_UNIT_PALLET
    ];


    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function productCategory()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }
    public function scopeForRack($query, $rackId)
    {
        return $query->where('rack_id', $rackId);
    }

    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';

    public static array $statuses = [
        self::STATUS_ACTIVE,
        self::STATUS_INACTIVE
    ];

    public static function getTranslatedTypes(): array
    {
        $result = [];
        foreach (self::$types as $type) {
            $result[] = [
                'key' => $type,
                'value' => trans("container.{$type}")
            ];
        }

        return $result;
    }

    public function getTranslatedType()
    {
        $result = [];
        if (is_array($this->type)) {
            foreach ($this->type as $type) {
                $result[] = [
                    'key' => $type,
                    'value' => trans("container.{$type}")
                ];
            }
        } else {
            $result[] = [
                'key' => $this->type,
                'value' => trans("container.{$this->type}")
            ];
        }

        return $result;
    }

    public static function getTranslatedCountingUnits()
    {
        $result = [];
        foreach (self::$countingUnits as $unit) {
            $result[] = [
                'key' => $unit,
                'value' => $unit
            ];
        }

        return $result;
    }

    public static function getTranslatedStatuses(): array
    {
        $result = [];
        foreach (self::$statuses as $status) {
            $result[] = [
                'key' => $status,
                'value' => trans("container.{$status}")
            ];
        }

        return $result;
    }

    public function warehouseDocuments()
    {
        return $this->belongsToMany(
            WarehouseDocument::class,
            'warehouse_document_has_products'
        )
            ->withPivot(['id', 'rack_id', 'unit', 'count'])
            ->withTimestamps();
    }

    public function warehouses()
    {
        return $this->belongsToMany(
            WarehouseProduct::class,
            'warehouse_has_products',
        )
            ->withPivot(['rack_id', 'main_counting_unit', 'coefficient', 'sub_counting_unit', 'beginning_inventory', 'quantity'])
            ->withTimestamps();
    }

    public function warehouseProducts()
    {
        return $this->hasMany(WarehouseProduct::class, 'product_id');
    }

    public function hasSameCountingUnits()
    {
        return $this->main_counting_unit == $this->sub_counting_unit;
    }

    public function getTotalQuantity()
    {
        $sum = 0;

        $this->warehouseProducts()->each(function ($wp) use (&$sum) {
            $sum += $wp->quantity;
        });

        return $sum;
    }


    public function warehouseDocumentProducts()
    {
        return $this->hasMany(WarehouseDocumentProduct::class, 'product_id');
    }

    public function computeTotalQuantities()
    {
        $this->warehouseDocumentProducts()->each(function ($documentHasProduct) {
            $warehouseDocument = $documentHasProduct->warehouseDocument;

            $warehouseHasProduct = $this->warehouseProducts()
                ->where('warehouse_id', $warehouseDocument->warehouse_id)
                ->where('rack_id', $documentHasProduct->rack_id)
                ->first();

            $warehouseHasProduct->update([
                'quantity' => 0
            ]);

//            $warehouseHasProduct->computeQuantity($documentHasProduct->count, $documentHasProduct->unit, $warehouseDocument->type);
        });
    }
}
