<?php

use App\Livewire\Admin\Pages\Factor\CreateInvoice;
use App\Livewire\Admin\Pages\Factor\GetAllInvoicesList;
use App\Livewire\Admin\Pages\Factor\UpdateInvoice;
use Illuminate\Support\Facades\Route;
use Modules\Finance\Livewire\AccountingAccount\Admin\GetAccountingAccountsList;
use Modules\Finance\Livewire\AccountingAccount\Admin\GetAccountingDetailsAccountsList;
use Modules\Finance\Livewire\AccountingAccount\Admin\GetAccountingSpecifiedAccountsList;
use Modules\Finance\Livewire\AccountingDocument\Admin\GetDocument;
use Modules\Finance\Livewire\AccountingDocument\Admin\GetDocumentList;
use Modules\Finance\Livewire\AccountingDocument\Admin\GetReceivedCashPaymentsList;
use Modules\Finance\Livewire\AccountingDocument\Admin\GetReceivedChequePaymentsList;
use Modules\Finance\Livewire\AccountingDocument\Admin\UpdateTenantsFinancialTransaction;
use Modules\Finance\Livewire\Invoice\Admin\CreatePurchaseInvoice;
use Modules\Finance\Livewire\Invoice\Admin\UpdatePurchaseInvoice;
use Modules\Identity\Http\Middleware\EnsureAdminIsAuthenticated;


Route::middleware([
    'auth:admin',
    EnsureAdminIsAuthenticated::class,
])->group(function () {

    Route::get('admin/table/user/edit-financial/{id}', UpdateTenantsFinancialTransaction::class);

    Route::get('admin/factor/add/{user_id}/{order_id?}', CreateInvoice::class);
    Route::get('admin/factor/edit/{factor_id}', UpdateInvoice::class);
    Route::get('admin/factor/show', GetAllInvoicesList::class);
    Route::get('admin/factor/buy/add', CreatePurchaseInvoice::class);
    Route::get('admin/factor/buy/edit/{factor_id}', UpdatePurchaseInvoice::class);

    Route::get('admin/financial/definition', GetAccountingAccountsList::class);
    Route::get('admin/financial/definition/moeen/{kol_id}', GetAccountingSpecifiedAccountsList::class);
    Route::get('admin/financial/definition/detailed/{kol_id}/{moeen_id}', GetAccountingDetailsAccountsList::class);

    Route::get('admin/financial/pay/cash-receive', GetReceivedCashPaymentsList::class);
    Route::get('admin/financial/pay/check-receive', GetReceivedChequePaymentsList::class);

    Route::get('admin/financial/document', GetDocumentList::class);
    Route::get('admin/financial/document/single/{id}', GetDocument::class);

});
