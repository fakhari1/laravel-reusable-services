<?php

namespace Modules\Finance\Models\Account\Traits\Specific;

trait SpecificConstants
{
    public const STATUS_ACTIVE = 'فعال';
    public const STATUS_INACTIVE = 'غیر فعال';

    public static array $statuses = [
        self::STATUS_INACTIVE,
        self::STATUS_ACTIVE,
    ];

    public const ACCOUNT_BANK_INVENTORY = 'موجودی نزد بانک';
    public const SLUG_BANK_INVENTORY = 'specific-bank-inventory';
    public const ACCOUNT_CASH_INVENTORY = 'موجودی نزد صندوق';
    public const SLUG_CASH_INVENTORY = 'specific-cash-inventory';
    public const ACCOUNT_PETTY_CASH_INVENTORY = 'موجودی نزد تنخواه گردان';
    public const SLUG_PETTY_CASH_INVENTORY = 'specific-petty-cash-inventory';
    public const ACCOUNT_CUSTOMERS = 'مشتریان';
    public const SLUG_CUSTOMERS = 'specific-customers';
    public const ACCOUNT_SALE_VALUE_ADDED_TAX = 'مالیات ارزش افزوده فروش';
    public const SLUG_SALE_VALUE_ADDED_TAX = 'specific-sale-value-added-tax';
    public const ACCOUNT_PURCHASE_VALUE_ADDED_TAX = 'مالیات ارزش افزوده خرید';
    public const SLUG_PURCHASE_VALUE_ADDED_TAX = 'specific-purchase-value-added-tax';
    public const ACCOUNT_PURCHASE_TAX = 'مالیات عوارض خرید';
    public const SLUG_PURCHASE_TAX = 'specific-purchase-tax';
    public const ACCOUNT_SALE_TAX = 'مالیات عوارض فروش';
    public const SLUG_SALE_TAX = 'specific-sale-tax';
    public const ACCOUNT_SALE = 'فروش';
    public const SLUG_SALE = 'specific-sale';
    public const ACCOUNT_PURCHASE = 'خرید';
    public const SLUG_PURCHASE = 'specific-purchase';
    public const ACCOUNT_SALE_DISCOUNT = 'تخفیف فروش';
    public const SLUG_SALE_DISCOUNT = 'specific-sale-discount';
    public const ACCOUNT_SALE_RETURNS = 'برگشت از فروش';
    public const SLUG_SALE_RETURNS = 'specific-sale-returns';
    public const ACCOUNT_PURCHASE_DISCOUNT = 'تخفیف خرید';
    public const SLUG_PURCHASE_DISCOUNT = 'specific-purchase-discount';
    public const ACCOUNT_PURCHASE_RETURNS = 'برگشت از خرید';
    public const SLUG_PURCHASE_RETURNS = 'specific-purchase-returns';



    public const NATURE_DEBIT = 'بدهکار';
    public const NATURE_CREDIT = 'بستانکار';

    public static array $natures = [
        self::NATURE_DEBIT,
        self::NATURE_CREDIT,
    ];
}
