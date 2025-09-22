<?php

namespace Modules\Finance\Models\Account\Traits\Group;

trait GroupConstants
{

    public const GROUP_CURRENT_ASSETS = 'دارایی های جاری';
    public const GROUP_NON_CURRENT_ASSETS = 'دارایی های غیر جاری';
    public const GROUP_CURRENT_DEBTS = 'بدهی های جاری';
    public const GROUP_NON_CURRENT_DEBTS = 'بدهی های غیر جاری';
    public const GROUP_SHAREHOLDERS_EQUITY = 'حقوق صاحبان سهام';
    public const GROUP_SALE_AND_INCOME = 'فروش و درآمد';
    public const GROUP_COST = 'هزینه';
    public const GROUP_PURCHASE = 'خرید';
    public const GROUP_INTERFACE_ACCOUNTS = 'حساب های رابط';
    public const GROUP_MEMORANDUM_ACCOUNTS = 'حساب های انتظامی';

    public static array $groups = [
        self::GROUP_COST,
        self::GROUP_SHAREHOLDERS_EQUITY,
        self::GROUP_SALE_AND_INCOME,
        self::GROUP_NON_CURRENT_ASSETS,
        self::GROUP_CURRENT_DEBTS,
        self::GROUP_NON_CURRENT_DEBTS,
        self::GROUP_CURRENT_ASSETS,
        self::GROUP_INTERFACE_ACCOUNTS,
        self::GROUP_MEMORANDUM_ACCOUNTS,
    ];

    public const NATURE_DEBIT = 'بدهکار';
    public const NATURE_CREDIT = 'بستانکار';
    public const NATURE_CHANGEABLE = 'بدهکار/بستانکار';
    public static array $natures = [
        self::NATURE_DEBIT,
        self::NATURE_CREDIT,
        self::NATURE_CHANGEABLE
    ];

    public const TYPE_PERMANENT = 'دائم';
    public const TYPE_TEMPORARY = 'موقت';

    public static $types = [
        self::TYPE_PERMANENT,
        self::TYPE_TEMPORARY,
    ];

}
