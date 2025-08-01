<?php

namespace Modules\Modules\Warehouse\Http\Controllers\Api\Product;

use Modules\Finance\Http\Resources\AccountingDetailedAccountResource;
use Modules\Finance\Models\AccountingDetailedAccount;
use Modules\Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Modules\Shared\Services\HttpRequestHandler;
use Modules\Modules\Shared\Services\Responder;
use Modules\Modules\Warehouse\Http\Resources\ProductCategory\ProductCategoryResource;
use Modules\Modules\Warehouse\Http\Resources\Warehouse\WarehouseResource;
use Modules\Modules\Warehouse\Models\Product;
use Modules\Modules\Warehouse\Models\ProductCategory;
use Modules\Modules\Warehouse\Models\Warehouse;
use Modules\Warehouse\Http\Resources\Warehouse\WarehouseDocumentResource;
use OpenApi\Annotations as OA;
use function Modules\Warehouse\Http\Controllers\Api\Product\app;

/**
 * @OA\Get(
 *     path="/api/products/get-create-update-data",
 *     operationId="getProductCreateData",
 *     tags={"Products"},
 *     summary="Get product create data",
 *     description="Returns product create data data",
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(ref="#/components/schemas/Product")
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="Forbidden"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Product not found"
 *     )
 * )
 */
class GetProductCreateUpdateData extends BaseCrudHandler
{
    public function handle(array $attributes = [])
    {
        $productCategories = ProductCategory::ForTenant($this->tenant->id)->where('parent_id', null)->get();
        $productsRequestResponse = app(HttpRequestHandler::class)->get('https://dev.dasterang.ir/api/products/all');
        $warehouses = Warehouse::ForTenant($this->tenant->id)->get();
//        $accounts = AccountingDetailedAccount::ForTenant($this->tenant->id)->get();
        $types = Product::getTranslatedTypes();
        $statuses = Product::getTranslatedStatuses();

        return Responder::success([
            'product_categories' => ProductCategoryResource::collection($productCategories),
            'products' => $productsRequestResponse['data']['products'],
            'warehouses' => WarehouseResource::collection($warehouses),
//            'accounts' => AccountingDetailedAccountResource::collection($accounts),
            'accounts' => [],
            'types' => $types,
            'statuses' => $statuses,
        ]);
    }
}
