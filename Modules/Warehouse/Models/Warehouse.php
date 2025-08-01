<?php

namespace Modules\Modules\Warehouse\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Finance\Models\AccountingAccount;
use Modules\Finance\Models\AccountingDetailedAccount;
use Modules\Identity\Models\Address;
use Modules\Identity\Models\TenantStaff;
use Modules\Tenancy\Models\Tenant;
use OpenApi\Annotations as OA;


/**
 * @OA\Schema(
 *     schema="Warehouse",
 *     type="object",
 *     title="Warehouse",
 *     required={"code", "type"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="tenant_id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="warehouse 1"),
 *     @OA\Property(property="code", type="string", example="WH001"),
 *     @OA\Property(property="type", type="string", example="main"),
 *     @OA\Property(property="storekeeper_id", type="integer", nullable=true, example=1),
 *     @OA\Property(property="address", type="string"),
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
class Warehouse extends Model
{
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
        return $this->hasMany(Rack::class);
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

}
