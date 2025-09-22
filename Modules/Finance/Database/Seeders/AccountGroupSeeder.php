<?php

namespace Modules\Finance\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Finance\Models\Account\AccountingAccountGroup;
use Modules\Tenancy\Models\Tenant;

class AccountGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $accountGroups = [
            [
                'title' => 'دارایی های جاری',
                'slug' => AccountingAccountGroup::GROUP_CURRENT_ASSETS,
                'nature' => AccountingAccountGroup::NATURE_DEBIT,
                'type' => AccountingAccountGroup::TYPE_PERMANENT
            ],
            [
                'title' => 'دارایی های غیر جاری',
                'slug' => AccountingAccountGroup::GROUP_NON_CURRENT_ASSETS,
                'nature' => AccountingAccountGroup::NATURE_DEBIT,
                'type' => AccountingAccountGroup::TYPE_PERMANENT
            ],
            [
                'title' => 'بدهی های جاری',
                'slug' => AccountingAccountGroup::GROUP_CURRENT_DEBTS,
                'nature' => AccountingAccountGroup::NATURE_CREDIT,
                'type' => AccountingAccountGroup::TYPE_PERMANENT
            ],
            [
                'title' => 'بدهی های غیر جاری',
                'slug' => AccountingAccountGroup::GROUP_NON_CURRENT_DEBTS,
                'nature' => AccountingAccountGroup::NATURE_CREDIT,
                'type' => AccountingAccountGroup::TYPE_PERMANENT
            ],
            [
                'title' => 'حقوق صاحبان سهام',
                'slug' => AccountingAccountGroup::GROUP_SHAREHOLDERS_EQUITY,
                'nature' => AccountingAccountGroup::NATURE_CREDIT,
                'type' => AccountingAccountGroup::TYPE_PERMANENT
            ],
            [
                'title' => 'فروش و درآمد',
                'slug' => AccountingAccountGroup::GROUP_SALE_AND_INCOME,
                'nature' => AccountingAccountGroup::NATURE_CREDIT,
                'type' => AccountingAccountGroup::TYPE_TEMPORARY
            ],
            [
                'title' => 'هزینه',
                'slug' => AccountingAccountGroup::GROUP_COST,
                'nature' => AccountingAccountGroup::NATURE_DEBIT,
                'type' => AccountingAccountGroup::TYPE_TEMPORARY
            ],
            [
                'title' => 'خرید',
                'slug' => AccountingAccountGroup::GROUP_PURCHASE,
                'nature' => AccountingAccountGroup::NATURE_DEBIT,
                'type' => AccountingAccountGroup::TYPE_TEMPORARY
            ],
            [
                'title' => 'حساب های رابط',
                'slug' => AccountingAccountGroup::GROUP_INTERFACE_ACCOUNTS,
                'nature' => AccountingAccountGroup::NATURE_CHANGEABLE,
                'type' => AccountingAccountGroup::TYPE_PERMANENT
            ],
            [
                'title' => 'حساب های انتظامی',
                'slug' => AccountingAccountGroup::GROUP_MEMORANDUM_ACCOUNTS,
                'nature' => AccountingAccountGroup::NATURE_CHANGEABLE,
                'type' => AccountingAccountGroup::TYPE_PERMANENT
            ],
        ];
        Tenant::all()->each(function ($tenant) use ($accountGroups) {
            foreach ($accountGroups as $key => $group) {
                AccountingAccountGroup::create([
                    'tenant_id' => $tenant->id,
                    'title' => $group['title'],
                    'slug' => $group['slug'],
                    'nature' => $group['nature'],
                    'type' => $group['type'],
                ]);
            }
        });
    }
}
