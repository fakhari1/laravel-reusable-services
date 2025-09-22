<?php

namespace Modules\Finance\Providers;

use App\Livewire\Admin\Pages\Table\User\UpdateTenantsFinancialTransaction;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Modules\Finance\Database\Seeders\AccountGroupSeeder;
use Modules\Finance\Database\Seeders\FiscalYearSeeder;
use Modules\Finance\Database\Seeders\AccountSeeder;
use Modules\Finance\Livewire\AccountingAccount\Admin\GetAccountingAccountsList;
use Modules\Finance\Livewire\AccountingAccount\Admin\GetAccountingDetailsAccountsList;
use Modules\Finance\Livewire\AccountingAccount\Admin\GetAccountingSpecifiedAccountsList;
use Modules\Finance\Livewire\AccountingDocument\Admin\GetDocument;
use Modules\Finance\Livewire\AccountingDocument\Admin\GetDocumentList;
use Modules\Finance\Livewire\Invoice\Admin\CreateInvoice;
use Modules\Finance\Livewire\Invoice\Admin\CreatePurchaseInvoice;
use Modules\Finance\Livewire\Invoice\Admin\GetInvoice;
use Modules\Finance\Livewire\Invoice\Admin\UpdateInvoice;
use Modules\Finance\Livewire\Invoice\Admin\UpdatePurchaseInvoice;
use Modules\Finance\Livewire\Invoice\Tenant\GetOurTenantInvoicesList;
use Modules\Shared\Helpers\GlobalHelpers;

class FinanceServiceProvider extends ServiceProvider
{
    public function boot()
    {
        DatabaseSeeder::$seeders[11] = FiscalYearSeeder::class;
        DatabaseSeeder::$seeders[12] = AccountGroupSeeder::class;
        DatabaseSeeder::$seeders[13] = AccountSeeder::class;

        $this->loadMigrationsFrom(GlobalHelpers::modulePath('Finance') . 'Database/Migrations/');
        $this->loadViewsFrom(GlobalHelpers::modulePath('Finance') . 'Resources/Views', 'Finance');
        $this->loadRoutesFrom(GlobalHelpers::modulePath('Finance') . 'Routes/tenant/api_finance_routes.php');
        $this->loadRoutesFrom(GlobalHelpers::modulePath('Finance') . 'Routes/tenant/web_finance_routes.php');
        $this->loadRoutesFrom(GlobalHelpers::modulePath('Finance') . 'Routes/admin/web_finance_routes.php');
        $this->loadLivewireComponents();
    }

    public function loadLivewireComponents()
    {
        $components = [
            'create-invoice' => CreateInvoice::class,
            'create-purchase-invoice' => CreatePurchaseInvoice::class,
            'get-invoice' => GetInvoice::class,
            'update-invoice' => UpdateInvoice::class,
            'update-purchase-invoice' => UpdatePurchaseInvoice::class,
            'update-tenants-fiscal-transaction' => UpdateTenantsFinancialTransaction::class,

            'get-accounting-accounts-list' => GetAccountingAccountsList::class,
            'get-accounting-specified-accounts-list' => GetAccountingSpecifiedAccountsList::class,
            'get-details-accounts-list' => GetAccountingDetailsAccountsList::class,

            'get-document' => GetDocument::class,
            'get-document-list' => GetDocumentList::class,
//            'get-received-cash-payments-list' => GetReceivedCashPaymentsList::class,
//            'get-received-cheque-payments-list' => GetReceivedChequePaymentsList::class,

            'get-our-tenant-invoices-list' => GetOurTenantInvoicesList::class,
        ];

        foreach ($components as $alias => $class) {
            Livewire::component($alias, $class);
        }
    }
}
