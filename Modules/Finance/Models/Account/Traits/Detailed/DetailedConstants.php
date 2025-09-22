<?php

namespace Modules\Finance\Models\Account\Traits\Detailed;

use Modules\Finance\Models\Invoice\AccountingInvoice;
use Modules\Finance\Models\Transaction\AccountingTransaction;

trait DetailedConstants
{

    public const LEVEL_1 = 1;
    public const LEVEL_2 = 2;

    public static array $levels = [
        self::LEVEL_1,
        self::LEVEL_2,
    ];

    public const STATUS_ACTIVE = 'فعال';
    public const STATUS_INACTIVE = 'غیر فعال';

    public static array $statuses = [
        self::STATUS_INACTIVE,
        self::STATUS_ACTIVE,
    ];

    public const ACCOUNT_CASH_INVENTORY = 'صندوق';
    public const SLUG_CASH_INVENTORY = 'detailed-cash-inventory';
    public const ACCOUNT_SALE_VALUE_ADDED_TAX = 'ارزش افزوده فروش';
    public const SLUG_SALE_VALUE_ADDED_TAX = 'detailed-sale-value-added-tax';
    public const ACCOUNT_PURCHASE_VALUE_ADDED_TAX = 'ارزش افزوده خرید';
    public const SLUG_PURCHASE_VALUE_ADDED_TAX = 'detailed-purchase-value-added-tax';
    public const ACCOUNT_PURCHASE_TAX = 'عوارض خرید';
    public const SLUG_PURCHASE_TAX = 'detailed-purchase-tax';
    public const ACCOUNT_SALE_TAX = 'عوارض فروش';
    public const SLUG_SALE_TAX = 'detailed-sale-tax';
    public const ACCOUNT_SALE = 'فروش';
    public const SLUG_SALE = 'detailed-sale';
    public const ACCOUNT_PURCHASE = 'خرید';
    public const SLUG_PURCHASE = 'detailed-purchase';
    public const ACCOUNT_SALE_DISCOUNT = 'تخفیف فروش';
    public const SLUG_SALE_DISCOUNT = 'detailed-sale-discount';
    public const ACCOUNT_SALE_RETURNS = 'برگشت از فروش';
    public const SLUG_SALE_RETURNS = 'detailed-sale-returns';
    public const ACCOUNT_PURCHASE_DISCOUNT = 'تخفیف خرید';
    public const SLUG_PURCHASE_DISCOUNT = 'detailed-purchase-discount';
    public const ACCOUNT_PURCHASE_RETURNS = 'برگشت از خرید';
    public const SLUG_PURCHASE_RETURNS = 'detailed-purchase-returns';
    public const SUBJECT_TYPE_INVOICE = AccountingInvoice::class;
    public const SUBJECT_INVOICE = 'سند فاکتور';
    public const SUBJECT_TYPE_TRANSACTION = AccountingTransaction::class;
    public const SUBJECT_TRANSACTION = 'سند دریافت و پرداخت';
    public const SUBJECT_TYPE_MANUAL = null;
    public const SUBJECT_MANUAL = 'سند دستی';
    public const SUBJECT_TYPE_AUTOMATIC = null;
    public const SUBJECT_AUTOMATIC = 'سند اتوماتیک';

    public static array $subjects = [
        self::SUBJECT_AUTOMATIC,
        self::SUBJECT_MANUAL,
        self::SUBJECT_INVOICE,
        self::SUBJECT_TRANSACTION,
    ];

    public static array $subjectTypes = [
        self::SUBJECT_TYPE_INVOICE,
        self::SUBJECT_TYPE_TRANSACTION,
        self::SUBJECT_TYPE_MANUAL,
        self::SUBJECT_TYPE_AUTOMATIC,
    ];


}
