<?php

namespace Modules\Finance\Models\Invoice\Traits\PurchaseInvoice;

trait PurchaseConstants
{
    public const TYPE_CREDIT = 'اعتبار بانکی';
    public const TYPE_CASH = 'نقد';
    public const TYPE_INSTALLMENT = 'اقساط';

    public static array $types = [
        self::TYPE_CREDIT,
        self::TYPE_CASH,
        self::TYPE_INSTALLMENT,
    ];

    public const STATUS_DRAFT = 'پیش فاکتور';
    public const STATUS_PENDING = 'در حال بررسی';
    public const STATUS_CANCELLED = 'باطل شده';
    public const STATUS_PAID = 'پرداخت شده';
    public const STATUS_CONFIRMED = 'تایید شده';

    public static array $statuses = [
        self::STATUS_DRAFT,
        self::STATUS_PENDING,
        self::STATUS_CANCELLED,
        self::STATUS_PAID,
        self::STATUS_CONFIRMED,
    ];
}
