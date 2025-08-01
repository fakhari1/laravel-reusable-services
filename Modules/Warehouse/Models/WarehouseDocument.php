<?php

namespace Modules\Modules\Warehouse\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Identity\Models\TenantStaff;
use Modules\Tenancy\Models\Tenant;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="WarehouseDocuments",
 *     type="object",
 *     title="Warehouse",
 *     required={"tenant_id", "staff_id", "warehouse_id", "type"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="tenant_id", type="integer", example=1),
 *     @OA\Property(property="staff_id", type="integer", example=1),
 *     @OA\Property(property="warehouse_id", type="integer", example=1),
 *     @OA\Property(property="status", type="string", example="{active, inactive}"),
 *     @OA\Property(property="account_id", type="integer", nullable=true, example=1),
 *     @OA\Property(property="description", type="string", nullable=true, example="nullable"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-01T00:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-01T00:00:00Z"),
 *     @OA\Property(property="racks", type="array", @OA\Items(type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="name", type="string", example="100"),
 *                 @OA\Property(property="description", type="string", example="nullable", nullable=true),
 *         )
 *     )
 * )
 */
class WarehouseDocument extends Model
{
    public const TYPE_RECEIPT = 'receipt';
    public const TYPE_TRANSFER = 'transfer';

    public static array $types = [
        self::TYPE_RECEIPT,
        self::TYPE_TRANSFER,
    ];

    public const STATUS_APPROVED = 'approved';
    public const STATUS_PENDING = 'pending';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_COMPLETED = 'completed';

    public static array $statuses = [
        self::STATUS_APPROVED,
        self::STATUS_PENDING,
        self::STATUS_CANCELLED,
        self::STATUS_COMPLETED,
    ];

    protected $fillable = [
        'tenant_id',
        'staff_id',
        'warehouse_id',
        'type',
        'documentable_type',
        'documentable_id',
        'status',
        'description',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function staff()
    {
        return $this->belongsTo(TenantStaff::class, 'staff_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function documentable()
    {
        return $this->morphTo();
    }

    public function products()
    {
        return $this->hasMany(WarehouseDocumentProduct::class);
    }

    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }
}
