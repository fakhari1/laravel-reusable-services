<?php

namespace Modules\Modules\Warehouse\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Tenancy\Models\Tenant;
use OpenApi\Annotations as OA;
use function Modules\Warehouse\Models\trans;

/**
 * @OA\Schema(
 *     schema="ProductCategory",
 *     type="object",
 *     title="ProductCategory",
 *     required={"code", "type"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="tenant_id", type="integer", example=1),
 *     @OA\Property(property="parent_id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="product category 1"),
 *     @OA\Property(property="code", type="string", example="WH001"),
 *     @OA\Property(property="status", type="string", example="{active, inactive}"),
 *     @OA\Property(property="description", type="string", nullable=true, example="nullable"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-01T00:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-01T00:00:00Z")
 * )
 */
class ProductCategory extends Model
{
    protected $fillable = [
        'tenant_id',
        'code',
        'name',
        'description',
        'status',
        'parent_id'
    ];

    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';

    public static array $statuses = [
        self::STATUS_ACTIVE,
        self::STATUS_INACTIVE
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function parent()
    {
        return $this->belongsTo(__CLASS__, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(__CLASS__, 'parent_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    public static function getTranslatedStatuses()
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
