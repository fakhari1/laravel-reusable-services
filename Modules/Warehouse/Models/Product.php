<?php

namespace Modules\Modules\Warehouse\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Tenancy\Models\Tenant;
use OpenApi\Annotations as OA;
use function Modules\Warehouse\Models\trans;

/**
 * @OA\Schema(
 *     schema="Product",
 *     type="object",
 *     title="Product",
 *     required={"code", "type"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="tenant_id", type="integer", example=1),
 *     @OA\Property(property="dasterang_product_id", type="integer", example=1),
 *     @OA\Property(property="product_category_id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="product 1"),
 *     @OA\Property(property="main_counting_unit", type="integer", example=1),
 *     @OA\Property(property="sub_counting_unit", type="integer", example=1),
 *     @OA\Property(property="stock_count", type="integer", example=1),
 *     @OA\Property(property="coefficient", type="integer", example=1),
 *     @OA\Property(property="code", type="string", example="WH001"),
 *     @OA\Property(property="status", type="string", example="{active, inactive}"),
 *     @OA\Property(property="type", type="string", example="type01"),
 *     @OA\Property(property="description", type="string", nullable=true, example="nullable"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-01T00:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-01T00:00:00Z")
 * )
 */
class Product extends Model
{
    protected $fillable = [
        'tenant_id',
        'dasterang_product_id',
        'code',
        'product_category_id',
        'name',
        'main_counting_unit',
        'sub_counting_unit',
        'stock_count',
        'coefficient',
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
}
