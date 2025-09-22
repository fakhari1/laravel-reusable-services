<?php

namespace Modules\Warehouse\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Tenancy\Models\Tenant;
use OpenApi\Annotations as OA;

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

    public static function scopeGetHierarchicalCategoriesEloquent($query)
    {
        return self::buildHierarchy($query, null);
    }

    private static function buildHierarchy($query, $parentId = null)
    {
        $result = collect();

        $children = $query->where('parent_id', $parentId);

        $children->each(function ($category) use (&$result, &$query) {
            $result->push($category);
            $childCategories = self::buildHierarchy($query, $category->id);
            $result = $result->merge($childCategories);
        });

        return $result;
    }
}
