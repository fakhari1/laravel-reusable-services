<?php

namespace Modules\Finance\Models\Invoice\Traits\Invoice;

trait InvoiceConstants
{
    public const STATUS_DRAFT = 'پیش فاکتور';
    public const STATUS_PENDING = 'در حال بررسی';
    public const STATUS_CONFIRMED = 'تایید شده';
    public const STATUS_PAID = 'پرداخت شده';
    public const STATUS_CANCELLED = 'باطل شده';

    public static array $statuses = [
        self::STATUS_DRAFT,
        self::STATUS_PENDING,
        self::STATUS_CONFIRMED,
        self::STATUS_PAID,
        self::STATUS_CANCELLED,
    ];

    public const TYPE_SALE = 'فروش';
    public const TYPE_PURCHASE = 'خرید';

    public static array $types = [
        self::TYPE_SALE,
        self::TYPE_PURCHASE
    ];

}
