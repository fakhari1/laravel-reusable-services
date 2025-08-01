<?php

namespace Modules\Modules\Warehouse\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Tenancy\Models\Tenant;
use OpenApi\Annotations as OA;


/**
 * @OA\Schema(
 *     schema="Rack",
 *     type="object",
 *     title="Rack",
 *     required={"code", "type"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="tenant_id", type="integer", example=1),
 *     @OA\Property(property="warehouse_id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="rack 1"),
 *     @OA\Property(property="code", type="string", example="WH001"),
 *     @OA\Property(property="status", type="string", example="{active, inactive}"),
 *     @OA\Property(property="description", type="string", nullable=true, example="nullable"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-01T00:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-01T00:00:00Z")
 * )
 */
class Rack extends Model
{
    protected $guarded = [];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
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
}
