<?php

use App\Http\Middleware\AuthenticateFromCookie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Tenancy\Http\Middleware\EnsureStaffAuthenticatedWithToken;
use Modules\Warehouse\Http\Controllers\Api\Document\DestroyWarehouseDocument;
use Modules\Warehouse\Http\Controllers\Api\Document\GetAllWarehouseDocumentsList;
use Modules\Warehouse\Http\Controllers\Api\Document\GetWarehouseDocument;
use Modules\Warehouse\Http\Controllers\Api\Document\GetWarehouseDocumentCreateUpdateData;
use Modules\Warehouse\Http\Controllers\Api\Document\Product\DestroyWarehouseDocumentProduct;
use Modules\Warehouse\Http\Controllers\Api\Document\Product\StoreWarehouseDocumentProduct;
use Modules\Warehouse\Http\Controllers\Api\Document\Product\UpdateWarehouseDocumentProduct;
use Modules\Warehouse\Http\Controllers\Api\Document\Receipt\StoreWarehouseReceiptDocument;
use Modules\Warehouse\Http\Controllers\Api\Document\Receipt\UpdateWarehouseReceiptDocument;
use Modules\Warehouse\Http\Controllers\Api\Document\Transfer\StoreWarehouseTransferDocument;
use Modules\Warehouse\Http\Controllers\Api\Document\Transfer\UpdateWarehouseTransferDocument;
use Modules\Warehouse\Http\Controllers\Api\Product\DestroyProduct;
use Modules\Warehouse\Http\Controllers\Api\Product\GetAllProductsList;
use Modules\Warehouse\Http\Controllers\Api\Product\GetCutunixProductsList;
use Modules\Warehouse\Http\Controllers\Api\Product\GetProduct;
use Modules\Warehouse\Http\Controllers\Api\Product\GetProductCreateUpdateData;
use Modules\Warehouse\Http\Controllers\Api\Product\StoreProduct;
use Modules\Warehouse\Http\Controllers\Api\Product\UpdateProduct;
use Modules\Warehouse\Http\Controllers\Api\ProductCategory\DestroyProductCategory;
use Modules\Warehouse\Http\Controllers\Api\ProductCategory\GetAllProductCategoriesList;
use Modules\Warehouse\Http\Controllers\Api\ProductCategory\GetProductCategory;
use Modules\Warehouse\Http\Controllers\Api\ProductCategory\GetProductCategoryCreateUpdateData;
use Modules\Warehouse\Http\Controllers\Api\ProductCategory\StoreProductCategory;
use Modules\Warehouse\Http\Controllers\Api\ProductCategory\UpdateProductCategory;
use Modules\Warehouse\Http\Controllers\Api\Rack\DestroyRack;
use Modules\Warehouse\Http\Controllers\Api\Rack\StoreRack;
use Modules\Warehouse\Http\Controllers\Api\Rack\UpdateRack;
use Modules\Warehouse\Http\Controllers\Api\Warehouse\DestroyWarehouse;
use Modules\Warehouse\Http\Controllers\Api\Warehouse\GetAllWarehousesList;
use Modules\Warehouse\Http\Controllers\Api\Warehouse\GetWarehouse;
use Modules\Warehouse\Http\Controllers\Api\Warehouse\GetWarehouseCreateUpdateData;
use Modules\Warehouse\Http\Controllers\Api\Warehouse\StoreWarehouse;
use Modules\Warehouse\Http\Controllers\Api\Warehouse\UpdateWarehouse;

Route::middleware([
    AuthenticateFromCookie::class,
    'auth:api-tenant',
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
    Route::delete('warehouses/documents/{id}/delete', DestroyWarehouseDocument::class);
    Route::get('warehouses/documents/get-create-update-data', GetWarehouseDocumentCreateUpdateData::class);
    Route::post('warehouses/documents/{id}/products/store', StoreWarehouseDocumentProduct::class);
    Route::delete('warehouses/documents/products/{id}/delete', DestroyWarehouseDocumentProduct::class);
    Route::put('warehouses/documents/products/{id}/update', UpdateWarehouseDocumentProduct::class);

    Route::post('warehouses/documents/transfer-docs/store', StoreWarehouseTransferDocument::class);
    Route::put('warehouses/documents/transfer-docs/{id}/update', UpdateWarehouseTransferDocument::class);

    Route::post('warehouses/documents/receipt-docs/store', StoreWarehouseReceiptDocument::class);
    Route::put('warehouses/documents/receipt-docs/{id}/update', UpdateWarehouseReceiptDocument::class);


    Route::get('cutunix/products/all', GetCutunixProductsList::class);
    Route::get('products/all', GetAllProductsList::class);
    Route::get('products/{id}/get', GetProduct::class);
    Route::get('products/get-create-update-data', GetProductCreateUpdateData::class);
    Route::post('products/store', StoreProduct::class);
    Route::put('products/{id}/update', UpdateProduct::class);
    Route::delete('products/{id}/delete', DestroyProduct::class);

    Route::get('product-categories/all', GetAllProductCategoriesList::class);
    Route::get('product-categories/{id}/get', GetProductCategory::class);
    Route::get('product-categories/get-create-update-data', GetProductCategoryCreateUpdateData::class);
    Route::post('product-categories/store', StoreProductCategory::class);
    Route::put('product-categories/{id}/update', UpdateProductCategory::class);
    Route::delete('product-categories/{id}/delete', DestroyProductCategory::class);

});
