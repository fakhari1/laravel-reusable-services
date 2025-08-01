<?php

use App\Http\Middleware\AuthenticateFromCookie;
use Illuminate\Support\Facades\Route;
use Modules\Modules\Warehouse\Http\Controllers\Api\Document\DestroyWarehouseDocument;
use Modules\Modules\Warehouse\Http\Controllers\Api\Document\GetAllWarehouseDocumentsList;
use Modules\Modules\Warehouse\Http\Controllers\Api\Document\GetWarehouseDocument;
use Modules\Modules\Warehouse\Http\Controllers\Api\Document\Receipt\GetWarehouseReceiptDocumentCreateUpdateData;
use Modules\Modules\Warehouse\Http\Controllers\Api\Document\Receipt\StoreWarehouseReceiptDocument;
use Modules\Modules\Warehouse\Http\Controllers\Api\Document\Receipt\UpdateWarehouseReceiptDocument;
use Modules\Modules\Warehouse\Http\Controllers\Api\Document\Transfer\GetWarehouseTransferDocumentCreateUpdateData;
use Modules\Modules\Warehouse\Http\Controllers\Api\Document\Transfer\StoreWarehouseTransferDocument;
use Modules\Modules\Warehouse\Http\Controllers\Api\Document\Transfer\UpdateWarehouseTransferDocument;
use Modules\Modules\Warehouse\Http\Controllers\Api\Product\DestroyProduct;
use Modules\Modules\Warehouse\Http\Controllers\Api\Product\GetAllProductsList;
use Modules\Modules\Warehouse\Http\Controllers\Api\Product\GetCutunixProductsList;
use Modules\Modules\Warehouse\Http\Controllers\Api\Product\GetProduct;
use Modules\Modules\Warehouse\Http\Controllers\Api\Product\GetProductCreateUpdateData;
use Modules\Modules\Warehouse\Http\Controllers\Api\Product\StoreProduct;
use Modules\Modules\Warehouse\Http\Controllers\Api\Product\UpdateProduct;
use Modules\Modules\Warehouse\Http\Controllers\Api\ProductCategory\DestroyProductCategory;
use Modules\Modules\Warehouse\Http\Controllers\Api\ProductCategory\GetAllProductCategoriesList;
use Modules\Modules\Warehouse\Http\Controllers\Api\ProductCategory\GetProductCategory;
use Modules\Modules\Warehouse\Http\Controllers\Api\ProductCategory\GetProductCategoryCreateUpateData;
use Modules\Modules\Warehouse\Http\Controllers\Api\ProductCategory\StoreProductCategory;
use Modules\Modules\Warehouse\Http\Controllers\Api\ProductCategory\UpdateProductCategory;
use Modules\Modules\Warehouse\Http\Controllers\Api\Rack\DestroyRack;
use Modules\Modules\Warehouse\Http\Controllers\Api\Rack\StoreRack;
use Modules\Modules\Warehouse\Http\Controllers\Api\Rack\UpdateRack;
use Modules\Modules\Warehouse\Http\Controllers\Api\Warehouse\DestroyWarehouse;
use Modules\Modules\Warehouse\Http\Controllers\Api\Warehouse\GetAllWarehousesList;
use Modules\Modules\Warehouse\Http\Controllers\Api\Warehouse\GetWarehouse;
use Modules\Modules\Warehouse\Http\Controllers\Api\Warehouse\GetWarehouseCreateUpdateData;
use Modules\Modules\Warehouse\Http\Controllers\Api\Warehouse\StoreWarehouse;
use Modules\Modules\Warehouse\Http\Controllers\Api\Warehouse\UpdateWarehouse;
use Modules\Tenancy\Http\Middleware\EnsureStaffAuthenticatedWithToken;

Route::middleware([
    'api',
    EnsureStaffAuthenticatedWithToken::class
    // 'auth:api-tenant'
])->prefix('api')->group(function () {
    Route::get('warehouses/all', GetAllWarehousesList::class);
    Route::get('warehouses/{id}/get', GetWarehouse::class);
    Route::get('warehouses/get-create-update-data', GetWarehouseCreateUpdateData::class);
    Route::post('warehouses/store', StoreWarehouse::class);
    Route::put('warehouses/{id}/update', UpdateWarehouse::class);
    Route::delete('warehouses/{id}/delete', DestroyWarehouse::class);

    Route::post('warehouses/{id}/racks/store', StoreRack::class);
    Route::put('warehouses/racks/{id}/update', UpdateRack::class);
    Route::delete('warehouses/racks/{id}/delete', DestroyRack::class);

    Route::get('warehouses/documents/all', GetAllWarehouseDocumentsList::class);
    Route::get('warehouses/documents/{id}/get', GetWarehouseDocument::class);

    Route::get('warehouses/documents/transfer-docs/get-create-update-data', GetWarehouseTransferDocumentCreateUpdateData::class);
    Route::post('warehouses/documents/transfer-docs/store', StoreWarehouseTransferDocument::class);
    Route::put('warehouses/documents/transfer-docs/{id}/update', UpdateWarehouseTransferDocument::class);

    Route::get('warehouses/documents/receipt-docs/get-create-update-data', GetWarehouseReceiptDocumentCreateUpdateData::class);
    Route::post('warehouses/documents/receipt-docs/store', StoreWarehouseReceiptDocument::class);
    Route::put('warehouses/documents/receipt-docs/{id}/update', UpdateWarehouseReceiptDocument::class);

    Route::delete('warehouses/documents/{id}/delete', DestroyWarehouseDocument::class);

    Route::get('cutunix/products/all', GetCutunixProductsList::class);
    Route::get('products/all', GetAllProductsList::class);
    Route::get('products/{id}/get', GetProduct::class);
    Route::get('products/get-create-update-data', GetProductCreateUpdateData::class);
    Route::post('products/store', StoreProduct::class);
    Route::put('products/{id}/update', UpdateProduct::class);
    Route::delete('products/{id}/delete', DestroyProduct::class);

    Route::get('product-categories/all', GetAllProductCategoriesList::class);
    Route::get('product-categories/{id}/get', GetProductCategory::class);
    Route::get('product-categories/get-create-update-data', GetProductCategoryCreateUpateData::class);
    Route::post('product-categories/store', StoreProductCategory::class);
    Route::put('product-categories/{id}/update', UpdateProductCategory::class);
    Route::delete('product-categories/{id}/delete', DestroyProductCategory::class);

});
