<?php

namespace Modules\Finance\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Finance\Models\Account\AccountingAccountGroup;
use Modules\Finance\Models\Account\AccountingDetailedAccount;
use Modules\Finance\Models\Account\AccountingGeneralAccount;
use Modules\Finance\Models\Account\AccountingSpecificAccount;
use Modules\Tenancy\Models\Tenant;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $generalAccounts = [
            [
                'title' => AccountingGeneralAccount::ACCOUNT_CASH_AND_BANK,
                'slug' => AccountingGeneralAccount::SLUG_CASH_AND_BANK,
                'group_title' => AccountingAccountGroup::GROUP_CURRENT_ASSETS,
                'code' => '01'
            ],
            [
                'title' => AccountingGeneralAccount::ACCOUNT_DEBTORS,
                'slug' => AccountingGeneralAccount::SLUG_DEBTORS,
                'group_title' => AccountingAccountGroup::GROUP_CURRENT_ASSETS,
                'code' => '03'
            ],
            [
                'title' => AccountingGeneralAccount::ACCOUNT_INVENTORY,
                'slug' => AccountingGeneralAccount::SLUG_INVENTORY,
                'group_title' => AccountingAccountGroup::GROUP_CURRENT_ASSETS,
                'code' => '05'
            ],
            [
                'title' => AccountingGeneralAccount::ACCOUNT_PREPAYMENTS,
                'slug' => AccountingGeneralAccount::SLUG_PREPAYMENTS,
                'group_title' => AccountingAccountGroup::GROUP_CURRENT_ASSETS,
                'code' => '06'
            ],
            [
                'title' => AccountingGeneralAccount::ACCOUNT_TANGIBLE_FIXED_ASSETS,
                'slug' => AccountingGeneralAccount::SLUG_TANGIBLE_FIXED_ASSETS,
                'group_title' => AccountingAccountGroup::GROUP_NON_CURRENT_ASSETS,
                'code' => '07'
            ],
            [
                'title' => AccountingGeneralAccount::ACCOUNT_CREDITORS,
                'slug' => AccountingGeneralAccount::SLUG_CREDITORS,
                'group_title' => AccountingAccountGroup::GROUP_CURRENT_DEBTS,
                'code' => '12'
            ],
            [
                'title' => AccountingGeneralAccount::ACCOUNT_PAYABLE_DOCUMENTS,
                'slug' => AccountingGeneralAccount::SLUG_PAYABLE_DOCUMENTS,
                'group_title' => AccountingAccountGroup::GROUP_CURRENT_DEBTS,
                'code' => '13'
            ],
            [
                'title' => AccountingGeneralAccount::ACCOUNT_SALE,
                'slug' => AccountingGeneralAccount::SLUG_SALE,
                'group_title' => AccountingAccountGroup::GROUP_SALE_AND_INCOME,
                'code' => '22'
            ],
            [
                'title' => AccountingGeneralAccount::ACCOUNT_OTHER_INCOMES,
                'slug' => AccountingGeneralAccount::SLUG_OTHER_INCOMES,
                'group_title' => AccountingAccountGroup::GROUP_SALE_AND_INCOME,
                'code' => '24'
            ],
            [
                'title' => AccountingGeneralAccount::ACCOUNT_COST_OF_GOODS_SOLD,
                'slug' => AccountingGeneralAccount::SLUG_COST_OF_GOODS_SOLD,
                'group_title' => AccountingAccountGroup::GROUP_COST,
                'code' => '25'
            ],
            [
                'title' => AccountingGeneralAccount::ACCOUNT_COST_OF_PROVIDED_SERVICES,
                'slug' => AccountingGeneralAccount::SLUG_COST_OF_PROVIDED_SERVICES,
                'group_title' => AccountingAccountGroup::GROUP_COST,
                'code' => '26'
            ],
            [
                'title' => AccountingGeneralAccount::ACCOUNT_COSTS,
                'slug' => AccountingGeneralAccount::SLUG_COSTS,
                'group_title' => AccountingAccountGroup::GROUP_COST,
                'code' => '27'
            ],
            [
                'title' => AccountingGeneralAccount::ACCOUNT_OTHER_ACCOUNTS,
                'slug' => AccountingGeneralAccount::SLUG_OTHER_ACCOUNTS,
                'group_title' => AccountingAccountGroup::GROUP_COST,
                'code' => '29'
            ],
            [
                'title' => AccountingGeneralAccount::ACCOUNT_IN_COLLECT_DOCUMENTS,
                'slug' => AccountingGeneralAccount::SLUG_IN_COLLECT_DOCUMENTS,
                'group_title' => AccountingAccountGroup::GROUP_INTERFACE_ACCOUNTS,
                'code' => '35'
            ],
            [
                'title' => AccountingGeneralAccount::ACCOUNT_ASSETS_TOOLS,
                'slug' => AccountingGeneralAccount::SLUG_ASSETS_TOOLS,
                'group_title' => AccountingAccountGroup::GROUP_NON_CURRENT_ASSETS,
                'code' => '36'
            ],
            [
                'title' => AccountingGeneralAccount::ACCOUNT_INCOMES,
                'slug' => AccountingGeneralAccount::SLUG_INCOMES,
                'group_title' => AccountingAccountGroup::GROUP_SALE_AND_INCOME,
                'code' => '37'
            ],
            [
                'title' => AccountingGeneralAccount::ACCOUNT_ADVANCES_RECEIVED,
                'slug' => AccountingGeneralAccount::SLUG_ADVANCES_RECEIVED,
                'group_title' => AccountingAccountGroup::GROUP_CURRENT_ASSETS,
                'code' => '38'
            ],
            [
                'title' => AccountingGeneralAccount::ACCOUNT_TAX_PROVISION,
                'slug' => AccountingGeneralAccount::SLUG_TAX_PROVISION,
                'group_title' => AccountingAccountGroup::GROUP_INTERFACE_ACCOUNTS,
                'code' => '39'
            ],
            [
                'title' => AccountingGeneralAccount::ACCOUNT_SHORT_TERM_BORROWINGS,
                'slug' => AccountingGeneralAccount::SLUG_SHORT_TERM_BORROWINGS,
                'group_title' => AccountingAccountGroup::GROUP_CURRENT_DEBTS,
                'code' => '40'
            ],
            [
                'title' => AccountingGeneralAccount::ACCOUNT_SHAREHOLDERS_EQUITY,
                'slug' => AccountingGeneralAccount::SLUG_SHAREHOLDERS_EQUITY,
                'group_title' => AccountingAccountGroup::GROUP_SHAREHOLDERS_EQUITY,
                'code' => '41'
            ],
            [
                'title' => AccountingGeneralAccount::ACCOUNT_SALE_RETURNS_AND_DISCOUNT,
                'slug' => AccountingGeneralAccount::SLUG_SALE_RETURNS_AND_DISCOUNT,
                'group_title' => AccountingAccountGroup::GROUP_SALE_AND_INCOME,
                'code' => '42'
            ],
            [
                'title' => AccountingGeneralAccount::ACCOUNT_PURCHASE,
                'slug' => AccountingGeneralAccount::SLUG_PURCHASE,
                'group_title' => AccountingAccountGroup::GROUP_PURCHASE,
                'code' => '43'
            ],
            [
                'title' => AccountingGeneralAccount::ACCOUNT_PURCHASE_RETURNS_AND_DISCOUNT,
                'slug' => AccountingGeneralAccount::SLUG_PURCHASE_RETURNS_AND_DISCOUNT,
                'group_title' => AccountingAccountGroup::GROUP_PURCHASE,
                'code' => '44'
            ],
            [
                'title' => AccountingGeneralAccount::ACCOUNT_MEMORANDUM_ACCOUNT,
                'slug' => AccountingGeneralAccount::SLUG_MEMORANDUM_ACCOUNT,
                'group_title' => AccountingAccountGroup::GROUP_MEMORANDUM_ACCOUNTS,
                'code' => '45'
            ],
            [
                'title' => AccountingGeneralAccount::ACCOUNT_MEMORANDUM_COUNTERPART_ACCOUNT,
                'slug' => AccountingGeneralAccount::SLUG_MEMORANDUM_COUNTERPART_ACCOUNT,
                'group_title' => AccountingAccountGroup::GROUP_MEMORANDUM_ACCOUNTS,
                'code' => '46'
            ],
            [
                'title' => AccountingGeneralAccount::ACCOUNT_OPENING_BALANCE,
                'slug' => AccountingGeneralAccount::SLUG_OPENING_BALANCE,
                'group_title' => AccountingAccountGroup::GROUP_INTERFACE_ACCOUNTS,
                'code' => '47'
            ],
            [
                'title' => AccountingGeneralAccount::ACCOUNT_CLOSING_BALANCE,
                'slug' => AccountingGeneralAccount::SLUG_CLOSING_BALANCE,
                'group_title' => AccountingAccountGroup::GROUP_INTERFACE_ACCOUNTS,
                'code' => '48'
            ],
        ];

        $cashAndBankSpecificAccounts = [
            [
                'code' => '01',
                'title' => AccountingSpecificAccount::ACCOUNT_BANK_INVENTORY,
                'slug' => AccountingSpecificAccount::SLUG_BANK_INVENTORY,
            ],
            [
                'code' => '02',
                'title' => AccountingSpecificAccount::ACCOUNT_CASH_INVENTORY,
                'slug' => AccountingSpecificAccount::SLUG_CASH_INVENTORY,
            ],
            [
                'code' => '03',
                'title' => AccountingSpecificAccount::ACCOUNT_PETTY_CASH_INVENTORY,
                'slug' => AccountingSpecificAccount::SLUG_PETTY_CASH_INVENTORY,
            ]
        ];

        $customersSpecificAccounts = [
            [

                'code' => '01',
                'title' => AccountingSpecificAccount::ACCOUNT_CUSTOMERS,
                'slug' => AccountingSpecificAccount::SLUG_CUSTOMERS,
            ]
        ];
        $taxProvisionSpecificAccounts = [
            [
                'code' => '01',
                'title' => AccountingSpecificAccount::ACCOUNT_SALE_VALUE_ADDED_TAX,
                'slug' => AccountingSpecificAccount::SLUG_SALE_VALUE_ADDED_TAX,
            ],
            [
                'code' => '02',
                'title' => AccountingSpecificAccount::ACCOUNT_PURCHASE_VALUE_ADDED_TAX,
                'slug' => AccountingSpecificAccount::SLUG_PURCHASE_VALUE_ADDED_TAX,
                'nature' => AccountingSpecificAccount::NATURE_DEBIT,
            ],
            [
                'code' => '03',
                'title' => AccountingSpecificAccount::ACCOUNT_SALE_TAX,
                'slug' => AccountingSpecificAccount::SLUG_SALE_TAX,
            ],
            [
                'code' => '04',
                'title' => AccountingSpecificAccount::ACCOUNT_PURCHASE_TAX,
                'slug' => AccountingSpecificAccount::SLUG_PURCHASE_TAX,
            ],
        ];
        $saleValueAddedTaxAccount = [
            'code' => '001',
            'title' => AccountingDetailedAccount::ACCOUNT_SALE_VALUE_ADDED_TAX,
            'slug' => AccountingDetailedAccount::SLUG_SALE_VALUE_ADDED_TAX,
            'level' => 1,
        ];
        $purchaseValueAddedTaxAccount = [
            'code' => '002',
            'title' => AccountingDetailedAccount::ACCOUNT_PURCHASE_VALUE_ADDED_TAX,
            'slug' => AccountingDetailedAccount::SLUG_PURCHASE_VALUE_ADDED_TAX,
            'level' => 1,
        ];
        $saleTaxAccount = [
            'code' => '003',
            'title' => AccountingDetailedAccount::ACCOUNT_SALE_TAX,
            'slug' => AccountingDetailedAccount::SLUG_SALE_TAX,
            'level' => 1,
        ];
        $purchaseTaxAccount = [
            'code' => '004',
            'title' => AccountingDetailedAccount::ACCOUNT_PURCHASE_TAX,
            'slug' => AccountingDetailedAccount::SLUG_PURCHASE_TAX,
            'level' => 1,
        ];

        $cashDetailedAccount = [
            'code' => '001',
            'title' => AccountingDetailedAccount::ACCOUNT_CASH_INVENTORY,
            'slug' => AccountingDetailedAccount::SLUG_CASH_INVENTORY,
            'level' => 1,
        ];

        $customersDetailedAccounts = [
            [
                'code' => '001',
                'title' => 'ملت حسین فخاری',
                'level' => 1,
            ],
            [
                'code' => '002',
                'title' => 'سپه حسین فخاری',
                'level' => 1,
            ],
            [
                'code' => '003',
                'title' => 'ملی حسین فخاری',
                'level' => 1,
            ],
            [
                'code' => '004',
                'title' => 'ملت علی آذری',
                'level' => 1,
            ],
            [
                'code' => '005',
                'title' => 'سپه علی آذری',
                'level' => 1,
            ],
            [
                'code' => '006',
                'title' => 'ملی علی آذری',
                'level' => 1,
            ],
            [
                'code' => '007',
                'title' => 'ملت پویا کارشناس',
                'level' => 1,
            ],
            [
                'code' => '008',
                'title' => 'سپه پویا کارشناس',
                'level' => 1,
            ],
            [
                'code' => '009',
                'title' => 'ملی پویا کارشناس',
                'level' => 1,
            ],
        ];

        Tenant::all()->each(function ($tenant) use (
            $generalAccounts,
            $cashAndBankSpecificAccounts,
            $cashDetailedAccount,
            $customersSpecificAccounts,
            $customersDetailedAccounts,
            $taxProvisionSpecificAccounts,
            $saleValueAddedTaxAccount,
            $purchaseValueAddedTaxAccount,
            $saleTaxAccount,
            $purchaseTaxAccount,
        ) {

            foreach ($generalAccounts as $key => $acc) {

                $group = AccountingAccountGroup::ForTenant($tenant->id)
                    ->where('title', $acc['group_title'])
                    ->first();

                $createdGeneralAccount = AccountingGeneralAccount::create([
                    'code' => $acc['code'],
                    'tenant_id' => $tenant->id,
                    'group_id' => $group->id,
                    'title' => $acc['title'],
                    'slug' => $acc['slug'],
                    'nature' => $group->nature,
                ]);

                if ($acc['slug'] == AccountingGeneralAccount::SLUG_CASH_AND_BANK) {
                    foreach ($cashAndBankSpecificAccounts as $specificAccount) {
                        $createdSpecificAccount = AccountingSpecificAccount::create([
                            'tenant_id' => $tenant->id,
                            'general_account_id' => $createdGeneralAccount->id,
                            'code' => $specificAccount['code'],
                            'title' => $specificAccount['title'],
                            'slug' => $specificAccount['slug'],
                        ]);

                        if ($specificAccount['slug'] == AccountingSpecificAccount::SLUG_CASH_INVENTORY) {
                            AccountingDetailedAccount::create([
                                'tenant_id' => $tenant->id,
                                'specific_account_id' => $createdSpecificAccount->id,
                                'code' => $cashDetailedAccount['code'],
                                'title' => $cashDetailedAccount['title'],
                                'level' => $cashDetailedAccount['level']
                            ]);
                        }

                        if ($specificAccount['slug'] == AccountingSpecificAccount::SLUG_PETTY_CASH_INVENTORY) {
                            AccountingDetailedAccount::create([
                                'tenant_id' => $tenant->id,
                                'specific_account_id' => $createdSpecificAccount->id,
                                'code' => '001',
                                'title' => 'کارشناس (تنخواه گردان) 5941',
                                'level' => 1
                            ]);
                        }

                        if ($specificAccount['slug'] == AccountingSpecificAccount::SLUG_BANK_INVENTORY) {
                            $_1stDetailedAccount = AccountingDetailedAccount::create([
                                'tenant_id' => $tenant->id,
                                'specific_account_id' => $createdSpecificAccount->id,
                                'code' => '001',
                                'title' => 'بانک ملت',
                                'level' => 1
                            ]);

                            AccountingDetailedAccount::create([
                                'tenant_id' => $tenant->id,
                                'specific_account_id' => $createdSpecificAccount->id,
                                'parent_id' => $_1stDetailedAccount->id,
                                'code' => '001',
                                'title' => 'بانک ملت رسمی آریو وب 6706',
                                'level' => 2
                            ]);
                        }
                    }
                }

                if ($acc['slug'] == AccountingGeneralAccount::SLUG_DEBTORS) {
                    foreach ($customersSpecificAccounts as $specificAccount) {
                        $createdSpecificAccount = AccountingSpecificAccount::create([
                            'tenant_id' => $tenant->id,
                            'general_account_id' => $createdGeneralAccount->id,
                            'code' => $specificAccount['code'],
                            'title' => $specificAccount['title'],
                            'slug' => $specificAccount['slug'],
                        ]);

                        if ($specificAccount['slug'] == AccountingSpecificAccount::SLUG_CUSTOMERS) {
                            foreach ($customersDetailedAccounts as $customerDetailedAccount) {
                                AccountingDetailedAccount::create([
                                    'tenant_id' => $tenant->id,
                                    'specific_account_id' => $createdSpecificAccount->id,
                                    'code' => $customerDetailedAccount['code'],
                                    'title' => $customerDetailedAccount['title'],
                                    'level' => $customerDetailedAccount['level']
                                ]);
                            }
                        }
                    }
                }

                if ($acc['slug'] == AccountingGeneralAccount::SLUG_TAX_PROVISION) {
                    foreach ($taxProvisionSpecificAccounts as $specificAccount) {
                        $createdSpecificAccount = AccountingSpecificAccount::create([
                            'tenant_id' => $tenant->id,
                            'general_account_id' => $createdGeneralAccount->id,
                            'code' => $specificAccount['code'],
                            'title' => $specificAccount['title'],
                            'slug' => $specificAccount['slug'],
                        ]);
                        if ($specificAccount['slug'] == AccountingSpecificAccount::SLUG_SALE_VALUE_ADDED_TAX) {
                            AccountingDetailedAccount::create([
                                'tenant_id' => $tenant->id,
                                'specific_account_id' => $createdSpecificAccount->id,
                                'code' => $saleValueAddedTaxAccount['code'],
                                'title' => $saleValueAddedTaxAccount['title'],
                                'slug' => $saleValueAddedTaxAccount['slug'],
                                'level' => $saleValueAddedTaxAccount['level']
                            ]);
                        }

                        if ($specificAccount['slug'] == AccountingSpecificAccount::SLUG_PURCHASE_VALUE_ADDED_TAX) {
                            AccountingDetailedAccount::create([
                                'tenant_id' => $tenant->id,
                                'specific_account_id' => $createdSpecificAccount->id,
                                'code' => $purchaseValueAddedTaxAccount['code'],
                                'title' => $purchaseValueAddedTaxAccount['title'],
                                'slug' => $purchaseValueAddedTaxAccount['slug'],
                                'level' => $purchaseValueAddedTaxAccount['level']
                            ]);
                        }

                        if ($specificAccount['slug'] == AccountingSpecificAccount::SLUG_SALE_TAX) {
                            AccountingDetailedAccount::create([
                                'tenant_id' => $tenant->id,
                                'specific_account_id' => $createdSpecificAccount->id,
                                'code' => $saleTaxAccount['code'],
                                'title' => $saleTaxAccount['title'],
                                'slug' => $saleTaxAccount['slug'],
                                'level' => $saleTaxAccount['level']
                            ]);
                        }

                        if ($specificAccount['slug'] == AccountingSpecificAccount::SLUG_PURCHASE_TAX) {
                            AccountingDetailedAccount::create([
                                'tenant_id' => $tenant->id,
                                'specific_account_id' => $createdSpecificAccount->id,
                                'code' => $purchaseTaxAccount['code'],
                                'title' => $purchaseTaxAccount['title'],
                                'slug' => $purchaseTaxAccount['slug'],
                                'level' => $purchaseTaxAccount['level']
                            ]);
                        }
                    }
                }

                if ($acc['slug'] == AccountingGeneralAccount::SLUG_SALE) {
                    $createdSpecificAccount = AccountingSpecificAccount::create([
                        'tenant_id' => $tenant->id,
                        'general_account_id' => $createdGeneralAccount->id,
                        'code' => '01',
                        'title' => AccountingSpecificAccount::ACCOUNT_SALE,
                        'slug' => AccountingSpecificAccount::SLUG_SALE
                    ]);

                    AccountingDetailedAccount::create([
                        'tenant_id' => $tenant->id,
                        'specific_account_id' => $createdSpecificAccount->id,
                        'code' => '001',
                        'title' => AccountingDetailedAccount::ACCOUNT_SALE,
                        'slug' => AccountingDetailedAccount::SLUG_SALE,
                        'level' => 1
                    ]);
                }

                if ($acc['slug'] == AccountingGeneralAccount::SLUG_PURCHASE) {
                    $createdSpecificAccount = AccountingSpecificAccount::create([
                        'tenant_id' => $tenant->id,
                        'general_account_id' => $createdGeneralAccount->id,
                        'code' => '01',
                        'title' => AccountingSpecificAccount::ACCOUNT_PURCHASE,
                        'slug' => AccountingSpecificAccount::SLUG_PURCHASE
                    ]);

                    AccountingDetailedAccount::create([
                        'tenant_id' => $tenant->id,
                        'specific_account_id' => $createdSpecificAccount->id,
                        'code' => '001',
                        'title' => AccountingDetailedAccount::ACCOUNT_PURCHASE,
                        'slug' => AccountingDetailedAccount::SLUG_PURCHASE,
                        'level' => 1
                    ]);
                }

                if ($acc['slug'] == AccountingGeneralAccount::SLUG_SALE_RETURNS_AND_DISCOUNT) {
                    $createdSpecificAccount = AccountingSpecificAccount::create([
                        'tenant_id' => $tenant->id,
                        'general_account_id' => $createdGeneralAccount->id,
                        'code' => '01',
                        'title' => AccountingSpecificAccount::ACCOUNT_SALE_DISCOUNT,
                        'slug' => AccountingSpecificAccount::SLUG_SALE_DISCOUNT
                    ]);

                    AccountingDetailedAccount::create([
                        'tenant_id' => $tenant->id,
                        'specific_account_id' => $createdSpecificAccount->id,
                        'code' => '001',
                        'title' => AccountingDetailedAccount::ACCOUNT_SALE_DISCOUNT,
                        'slug' => AccountingDetailedAccount::SLUG_SALE_DISCOUNT,
                        'level' => 1
                    ]);

                    $createdSpecificAccount = AccountingSpecificAccount::create([
                        'tenant_id' => $tenant->id,
                        'general_account_id' => $createdGeneralAccount->id,
                        'code' => '02',
                        'title' => AccountingSpecificAccount::ACCOUNT_SALE_RETURNS,
                        'slug' => AccountingSpecificAccount::SLUG_SALE_RETURNS
                    ]);

                    AccountingDetailedAccount::create([
                        'tenant_id' => $tenant->id,
                        'specific_account_id' => $createdSpecificAccount->id,
                        'code' => '001',
                        'title' => AccountingDetailedAccount::ACCOUNT_SALE_RETURNS,
                        'slug' => AccountingDetailedAccount::SLUG_SALE_RETURNS,
                        'level' => 1
                    ]);
                }

                if ($acc['slug'] == AccountingGeneralAccount::SLUG_PURCHASE_RETURNS_AND_DISCOUNT) {
                    $createdSpecificAccount = AccountingSpecificAccount::create([
                        'tenant_id' => $tenant->id,
                        'general_account_id' => $createdGeneralAccount->id,
                        'code' => '01',
                        'title' => AccountingSpecificAccount::ACCOUNT_PURCHASE_DISCOUNT,
                        'slug' => AccountingSpecificAccount::SLUG_PURCHASE_DISCOUNT
                    ]);

                    AccountingDetailedAccount::create([
                        'tenant_id' => $tenant->id,
                        'specific_account_id' => $createdSpecificAccount->id,
                        'code' => '001',
                        'title' => AccountingDetailedAccount::ACCOUNT_PURCHASE_DISCOUNT,
                        'slug' => AccountingDetailedAccount::SLUG_PURCHASE_DISCOUNT,
                        'level' => 1
                    ]);

                    $createdSpecificAccount = AccountingSpecificAccount::create([
                        'tenant_id' => $tenant->id,
                        'general_account_id' => $createdGeneralAccount->id,
                        'code' => '02',
                        'title' => AccountingSpecificAccount::ACCOUNT_PURCHASE_RETURNS,
                        'slug' => AccountingSpecificAccount::SLUG_PURCHASE_RETURNS
                    ]);

                    AccountingDetailedAccount::create([
                        'tenant_id' => $tenant->id,
                        'specific_account_id' => $createdSpecificAccount->id,
                        'code' => '001',
                        'title' => AccountingDetailedAccount::ACCOUNT_PURCHASE_RETURNS,
                        'slug' => AccountingDetailedAccount::SLUG_PURCHASE_RETURNS,
                        'level' => 1
                    ]);
                }
            }
        });
    }
}
