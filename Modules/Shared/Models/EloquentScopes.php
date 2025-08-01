<?php

namespace Modules\Modules\Shared\Models;

use function Modules\Shared\Models\auth;

trait EloquentScopes
{
    public function scopeForOurTenant($query)
    {
        return $query->where('tenant_id', auth('tenant')->user()->tenant->id);
    }
}
