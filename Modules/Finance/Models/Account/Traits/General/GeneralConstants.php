<?php

namespace Modules\Finance\Models\Account\Traits\General;

trait GeneralConstants
{
    public const ACCOUNT_CASH_AND_BANK = 'موجودی نقد و بانک';
    public const SLUG_CASH_AND_BANK = 'general-cash-and-bank';
    public const ACCOUNT_DEBTORS = 'بدهکاران (حساب های دریافتنی)';
    public const SLUG_DEBTORS = 'general-debtors';
    public const ACCOUNT_CREDITORS = 'بستانکاران (حساب های پرداختنی)';
    public const SLUG_CREDITORS = 'general-creditors';
    public const ACCOUNT_RECEIVABLE_DOCUMENTS = 'اسناد دریافتنی';
    public const SLUG_RECEIVABLE_DOCUMENTS = 'general-receivable-documents';
    public const ACCOUNT_PAYABLE_DOCUMENTS = 'اسناد پرداختنی';
    public const SLUG_PAYABLE_DOCUMENTS = 'general-payable-documents';
    public const ACCOUNT_IN_COLLECT_DOCUMENTS = 'اسناد در جریان وصول';
    public const SLUG_IN_COLLECT_DOCUMENTS = 'general-in-collect-documents';
    public const ACCOUNT_ASSETS_TOOLS = 'اموال، ماشین آلات و تجهیزات';
    public const SLUG_ASSETS_TOOLS = 'general-assets-tools';
    public const ACCOUNT_INCOMES = 'درآمدها';
    public const SLUG_INCOMES = 'general-incomes';
    public const ACCOUNT_OTHER_INCOMES = 'سایر درآمدها';
    public const SLUG_OTHER_INCOMES = 'general-other-incomes';
    public const ACCOUNT_INVENTORY = 'موجودی مواد و کالا';
    public const SLUG_INVENTORY = 'general-inventory';
    public const ACCOUNT_PREPAYMENTS = 'پیش پرداخت ها';
    public const SLUG_PREPAYMENTS = 'general-pre-payments';
    public const ACCOUNT_TANGIBLE_FIXED_ASSETS = 'دارایی های ثابت مشهود';
    public const SLUG_TANGIBLE_FIXED_ASSETS = 'general-tangible-fixed-assets';
    public const ACCOUNT_INTANGIBLE_FIXED_ASSETS = 'دارایی های ثابت نامشهود';
    public const SLUG_INTANGIBLE_FIXED_ASSETS = 'general-intangible-fixed-assets';
    public const ACCOUNT_TANGIBLE_CURRENT_ASSETS = 'دارایی های جاری مشهود';
    public const SLUG_TANGIBLE_CURRENT_ASSETS = 'general-tangible-current-assets';
    public const ACCOUNT_INTANGIBLE_CURRENT_ASSETS = 'دارایی های جاری نامشهود';
    public const SLUG_INTANGIBLE_CURRENT_ASSETS = 'general-tangible-current-assets';
    public const ACCOUNT_ADVANCES_RECEIVED = 'پیش دریافت ها';
    public const SLUG_ADVANCES_RECEIVED = 'general-advances-received';
    public const ACCOUNT_TAX_PROVISION = 'ذخیره مالیات';
    public const SLUG_TAX_PROVISION = 'general-tax-provision';
    public const ACCOUNT_SHORT_TERM_BORROWINGS = 'تسهیلات مالی کوتاه مدت';
    public const SLUG_SHORT_TERM_BORROWINGS = 'general-short-term-borrowings';
    public const ACCOUNT_SHAREHOLDERS_EQUITY = 'حقوق صاحبان سهام';
    public const SLUG_SHAREHOLDERS_EQUITY = 'general-shareholders-equity';
    public const ACCOUNT_COSTS = 'هزینه ها';
    public const SLUG_COSTS = 'general-costs';
    public const ACCOUNT_COST_OF_GOODS_SOLD = 'قیمت تمام شده ی کالای فروش رفته';
    public const SLUG_COST_OF_GOODS_SOLD = 'general-cogs';
    public const ACCOUNT_COST_OF_PROVIDED_SERVICES = 'بهای تمام شدهی خدمات ارائه شده';
    public const SLUG_COST_OF_PROVIDED_SERVICES = 'general-cops';
    public const ACCOUNT_SALE = 'فروش';
    public const SLUG_SALE = 'general-sale';
    public const ACCOUNT_SALE_RETURNS_AND_DISCOUNT = 'برگشت از فروش و تخفیف';
    public const SLUG_SALE_RETURNS_AND_DISCOUNT = 'general-sale-returns-and-discount';
    public const ACCOUNT_PURCHASE = 'خرید';
    public const SLUG_PURCHASE = 'general-purchase';
    public const ACCOUNT_PURCHASE_RETURNS_AND_DISCOUNT = 'برگشت از خرید و تخفیف';
    public const SLUG_PURCHASE_RETURNS_AND_DISCOUNT = 'general-purchase-returns-and-discount';
    public const ACCOUNT_MEMORANDUM_ACCOUNT = 'حساب های انتظامی';
    public const SLUG_MEMORANDUM_ACCOUNT = 'general-memorandum';
    public const ACCOUNT_MEMORANDUM_COUNTERPART_ACCOUNT = 'طرف حساب های انتظامی';
    public const SLUG_MEMORANDUM_COUNTERPART_ACCOUNT = 'general-memorandum-counterpart';
    public const ACCOUNT_OPENING_BALANCE = 'تراز افتتاحیه';
    public const SLUG_OPENING_BALANCE = 'general-opening-balance';
    public const ACCOUNT_CLOSING_BALANCE = 'تراز اختتامیه';
    public const SLUG_CLOSING_BALANCE = 'general-closing-balance';
    public const ACCOUNT_OTHER_ACCOUNTS = 'سایر حساب ها';
    public const SLUG_OTHER_ACCOUNTS = 'general-other-accounts';

    public static array $accounts = [
        self::ACCOUNT_CASH_AND_BANK,
        self::ACCOUNT_DEBTORS,
        self::ACCOUNT_CREDITORS,
        self::ACCOUNT_RECEIVABLE_DOCUMENTS,
        self::ACCOUNT_PAYABLE_DOCUMENTS,
        self::ACCOUNT_IN_COLLECT_DOCUMENTS,
        self::ACCOUNT_ASSETS_TOOLS,
        self::ACCOUNT_INCOMES,
        self::ACCOUNT_INVENTORY,
        self::ACCOUNT_PREPAYMENTS,
        self::ACCOUNT_TANGIBLE_FIXED_ASSETS,
        self::SLUG_TANGIBLE_FIXED_ASSETS,
        self::ACCOUNT_INTANGIBLE_FIXED_ASSETS,
        self::SLUG_INTANGIBLE_FIXED_ASSETS,
        self::ACCOUNT_TANGIBLE_CURRENT_ASSETS,
        self::SLUG_TANGIBLE_CURRENT_ASSETS,
        self::ACCOUNT_INTANGIBLE_CURRENT_ASSETS,
        self::SLUG_INTANGIBLE_CURRENT_ASSETS,
        self::ACCOUNT_ADVANCES_RECEIVED,
        self::ACCOUNT_TAX_PROVISION,
        self::ACCOUNT_SHORT_TERM_BORROWINGS,
        self::ACCOUNT_SHAREHOLDERS_EQUITY,
        self::ACCOUNT_COSTS,
        self::ACCOUNT_COST_OF_PROVIDED_SERVICES,
        self::ACCOUNT_COST_OF_GOODS_SOLD,
        self::ACCOUNT_SALE,
        self::ACCOUNT_SALE_RETURNS_AND_DISCOUNT,
        self::ACCOUNT_PURCHASE,
        self::ACCOUNT_PURCHASE_RETURNS_AND_DISCOUNT,
        self::ACCOUNT_MEMORANDUM_ACCOUNT,
        self::ACCOUNT_MEMORANDUM_COUNTERPART_ACCOUNT,
        self::ACCOUNT_OPENING_BALANCE,
    ];

    public const NATURE_DEBIT = 'بدهکار';
    public const NATURE_CREDIT = 'بستانکار';
    public const NATURE_CHANGEABLE = 'بدهکار/بستانکار';

    public static array $natures = [
        self::NATURE_DEBIT,
        self::NATURE_CREDIT,
        self::NATURE_CHANGEABLE,
    ];

    public const STATUS_ACTIVE = 'فعال';
    public const STATUS_INACTIVE = 'غیر فعال';

    public static array $statuses = [
        self::STATUS_INACTIVE,
        self::STATUS_ACTIVE,
    ];

}
