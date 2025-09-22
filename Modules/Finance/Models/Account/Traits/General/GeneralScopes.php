<?php

namespace Modules\Finance\Models\Account\Traits\General;

trait GeneralScopes
{

    public function scopeForGroup($query, $groupId)
    {
        return $query->where('group_id', $groupId);
    }
}
