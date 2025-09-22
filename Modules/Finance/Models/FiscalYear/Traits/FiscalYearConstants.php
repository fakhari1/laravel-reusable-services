<?php

namespace Modules\Finance\Models\FiscalYear\Traits;

trait FiscalYearConstants
{
    public const STATUS_ACTIVE = 'فعال';
    public const STATUS_INACTIVE = 'غیر فعال';
    public const STATUS_CLOSED = 'بسته شده';
    public static array $statuses = [
        self::STATUS_ACTIVE,
        self::STATUS_INACTIVE,
        self::STATUS_CLOSED
    ];

}
