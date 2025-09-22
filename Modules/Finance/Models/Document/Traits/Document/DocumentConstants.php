<?php

namespace Modules\Finance\Models\Document\Traits\Document;

trait DocumentConstants
{
    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';

    public static array $statuses = [
        self::STATUS_ACTIVE,
        self::STATUS_INACTIVE
    ];

}
