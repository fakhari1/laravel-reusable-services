<?php

use App\Http\Middleware\AuthenticateFromCookie;
use Illuminate\Support\Facades\Route;
use Modules\Finance\Http\Controllers\Account\AccountGroup\DestroyAccountGroup;
use Modules\Finance\Http\Controllers\Account\AccountGroup\GetAccountGroup;
use Modules\Finance\Http\Controllers\Account\AccountGroup\GetAccountGroupCreateUpdateData;
use Modules\Finance\Http\Controllers\Account\AccountGroup\GetAllAccountGroupsList;
use Modules\Finance\Http\Controllers\Account\AccountGroup\StoreAccountGroup;
use Modules\Finance\Http\Controllers\Account\AccountGroup\UpdateAccountGroup;
use Modules\Finance\Http\Controllers\Account\DetailedAccount\DestroyDetailedAccount;
use Modules\Finance\Http\Controllers\Account\DetailedAccount\GetAllDetailedAccountsList;
use Modules\Finance\Http\Controllers\Account\DetailedAccount\GetDetailedAccount;
use Modules\Finance\Http\Controllers\Account\DetailedAccount\GetDetailedAccountCreateUpdateData;
use Modules\Finance\Http\Controllers\Account\DetailedAccount\StoreDetailedAccount;
use Modules\Finance\Http\Controllers\Account\DetailedAccount\UpdateDetailedAccount;
use Modules\Finance\Http\Controllers\Account\GeneralAccount\DestroyGeneralAccount;
use Modules\Finance\Http\Controllers\Account\GeneralAccount\GetAllCodingAccountsList;
use Modules\Finance\Http\Controllers\Account\GeneralAccount\GetAllGeneralAccountsList;
use Modules\Finance\Http\Controllers\Account\GeneralAccount\GetGeneralAccount;
use Modules\Finance\Http\Controllers\Account\GeneralAccount\GetGeneralAccountCreateUpdateData;
use Modules\Finance\Http\Controllers\Account\GeneralAccount\StoreGeneralAccount;
use Modules\Finance\Http\Controllers\Account\GeneralAccount\UpdateGeneralAccount;
use Modules\Finance\Http\Controllers\Account\SpecificAccount\DestroySpecificAccount;
use Modules\Finance\Http\Controllers\Account\SpecificAccount\GetAllSpecificAccountsList;
use Modules\Finance\Http\Controllers\Account\SpecificAccount\GetSpecificAccount;
use Modules\Finance\Http\Controllers\Account\SpecificAccount\GetSpecificAccountCreateUpdateData;
use Modules\Finance\Http\Controllers\Account\SpecificAccount\StoreSpecificAccount;
use Modules\Finance\Http\Controllers\Account\SpecificAccount\UpdateSpecificAccount;
use Modules\Finance\Http\Controllers\Document\DestroyDocument;
use Modules\Finance\Http\Controllers\Document\GetAllDocumentsList;
use Modules\Finance\Http\Controllers\Document\GetDocument;
use Modules\Finance\Http\Controllers\Document\GetDocumentCreateUpdateData;
use Modules\Finance\Http\Controllers\Document\StoreDocument;
use Modules\Finance\Http\Controllers\Document\UpdateDocument;
use Modules\Finance\Http\Controllers\FiscalYear\GetAllFiscalYearsList;
use Modules\Finance\Http\Controllers\FiscalYear\GetActiveFiscalYear;
use Modules\Finance\Http\Controllers\FiscalYear\GetFiscalYearCreateUpdateData;
use Modules\Finance\Http\Controllers\FiscalYear\SetFiscalYearToActive;
use Modules\Finance\Http\Controllers\FiscalYear\StoreFiscalYear;
use Modules\Finance\Http\Controllers\FiscalYear\UpdateFiscalYear;
use Modules\Finance\Http\Controllers\Invoice\Invoice\GetAllInvoicesList;
use Modules\Finance\Http\Controllers\Invoice\Invoice\GetInvoice;
use Modules\Finance\Http\Controllers\Invoice\Purchase\GetPurchaseInvoiceCreateUpdateData;
use Modules\Finance\Http\Controllers\Invoice\Sale\DestroySaleInvoice;
use Modules\Finance\Http\Controllers\Invoice\Sale\GetSaleInvoiceCreateUpdateData;
use Modules\Finance\Http\Controllers\Invoice\Sale\StoreSaleInvoice;
use Modules\Finance\Http\Controllers\Invoice\Sale\UpdateSaleInvoice;

Route::middleware([
    AuthenticateFromCookie::class,
    'auth:api-tenant',
])->prefix('api')->group(function () {

    Route::post('workshops/accounting/fiscal-years/store', StoreFiscalYear::class);
    Route::get('workshops/accounting/fiscal-years/all', GetAllFiscalYearsList::class);
    Route::put('workshops/accounting/fiscal-years/{id}/update', UpdateFiscalYear::class);
    Route::get('workshops/accounting/fiscal-years/get-create-update-data', GetFiscalYearCreateUpdateData::class);
    Route::get('workshops/accounting/fiscal-years/default/get', GetActiveFiscalYear::class);
    Route::post('workshops/accounting/fiscal-years/default/set', SetFiscalYearToActive::class);

    Route::post('workshops/accounting/account-groups/store', StoreAccountGroup::class);
    Route::get('workshops/accounting/account-groups/{id}/get', GetAccountGroup::class);
    Route::get('workshops/accounting/account-groups/all', GetAllAccountGroupsList::class);
    Route::get('workshops/accounting/account-groups/get-create-update-data', GetAccountGroupCreateUpdateData::class);
    Route::put('workshops/accounting/account-groups/{id}/update', UpdateAccountGroup::class);
    Route::delete('workshops/accounting/account-groups/{id}/delete', DestroyAccountGroup::class);

    Route::get('workshops/accounting/accounts/coding/all', GetAllCodingAccountsList::class);
    Route::post('workshops/accounting/general-accounts/store', StoreGeneralAccount::class);
    Route::get('workshops/accounting/general-accounts/all', GetAllGeneralAccountsList::class);
    Route::get('workshops/accounting/general-accounts/{id}/get', GetGeneralAccount::class);
    Route::get('workshops/accounting/general-accounts/get-create-update-data', GetGeneralAccountCreateUpdateData::class);
    Route::put('workshops/accounting/general-accounts/{id}/update', UpdateGeneralAccount::class);
    Route::delete('workshops/accounting/general-accounts/{id}/delete', DestroyGeneralAccount::class);

    Route::post('workshops/accounting/specific-accounts/store', StoreSpecificAccount::class);
    Route::get('workshops/accounting/specific-accounts/all', GetAllSpecificAccountsList::class);
    Route::get('workshops/accounting/specific-accounts/{id}/get', GetSpecificAccount::class);
    Route::get('workshops/accounting/specific-accounts/get-create-update-data', GetSpecificAccountCreateUpdateData::class);
    Route::put('workshops/accounting/specific-accounts/{id}/update', UpdateSpecificAccount::class);
    Route::delete('workshops/accounting/specific-accounts/{id}/delete', DestroySpecificAccount::class);

    Route::post('workshops/accounting/detailed-accounts/store', StoreDetailedAccount::class);
    Route::get('workshops/accounting/detailed-accounts/{id}/get', GetDetailedAccount::class);
    Route::get('workshops/accounting/detailed-accounts/get-create-update-data', GetDetailedAccountCreateUpdateData::class);
    Route::get('workshops/accounting/detailed-accounts/all', GetAllDetailedAccountsList::class);
    Route::put('workshops/accounting/detailed-accounts/{id}/update', UpdateDetailedAccount::class);
    Route::delete('workshops/accounting/detailed-accounts/{id}/delete', DestroyDetailedAccount::class);

    Route::get('workshops/accounting/documents/get-create-update-data', GetDocumentCreateUpdateData::class);
    Route::get('workshops/accounting/documents/{id}/get', GetDocument::class);
    Route::get('workshops/accounting/documents/all', GetAllDocumentsList::class);
    Route::post('workshops/accounting/documents/store', StoreDocument::class);
    Route::put('workshops/accounting/documents/{id}/update', UpdateDocument::class);
    Route::delete('workshops/accounting/documents/{id}/delete', DestroyDocument::class);

    Route::get('workshops/accounting/invoices/all', GetAllInvoicesList::class);
    Route::get('workshops/accounting/invoices/{id}/get', GetInvoice::class);

    Route::get('workshops/accounting/invoices/sale-invoices/get-create-update-data', GetSaleInvoiceCreateUpdateData::class);
    Route::post('workshops/accounting/invoices/sale-invoices/store', StoreSaleInvoice::class);
    Route::put('workshops/accounting/invoices/sale-invoices/{id}/update', UpdateSaleInvoice::class);
    Route::delete('workshops/accounting/invoices/sale-invoices/{id}/delete', DestroySaleInvoice::class);

    Route::get('workshops/accounting/invoices/purchase-invoices/get-create-update-data', GetPurchaseInvoiceCreateUpdateData::class);
});
