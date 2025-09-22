<?php

namespace Modules\Finance\Models\Invoice\Traits\SaleInvoice;

trait SaleConstants
{
    public const TYPE_CASH = 'نقد';
    public const TYPE_CREDIT = 'اعتبار بانکی';
    public const TYPE_INSTALLMENT = 'اقساط';

    public static array $types = [
        self::TYPE_CASH,
        self::TYPE_CREDIT,
        self::TYPE_INSTALLMENT
    ];

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
}
