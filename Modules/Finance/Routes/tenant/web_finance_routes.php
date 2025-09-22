<?php

use Illuminate\Support\Facades\Route;
use Modules\Finance\Livewire\Invoice\Tenant\GetOurTenantInvoicesList;
use Modules\Tenancy\Http\Middleware\EnsureStaffIsAuthenticated;
use Modules\Tenancy\Http\Middleware\EnsureTheTenantIsSetUp;

Route::middleware([
    'auth:tenant',
    EnsureStaffIsAuthenticated::class,
    EnsureTheTenantIsSetUp::class,
])->group(function () {

    Route::get('workshops/{tenant:name}/finance/invoices/all', GetOurTenantInvoicesList::class)->name('tenant.finance.invoices.all');

});
