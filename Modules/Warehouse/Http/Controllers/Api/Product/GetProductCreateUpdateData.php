<?php

namespace Modules\Warehouse\Http\Controllers\Api\Product;

use Modules\Finance\Http\Resources\AccountingDetailedAccountResource;
use Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Shared\Services\HttpRequestHandler;
use Modules\Shared\Services\Responder;
use Modules\Warehouse\Http\Resources\Warehouse\WarehouseDocumentResource;
use Modules\Warehouse\Http\Resources\Warehouse\WarehouseResource;
use Modules\Warehouse\Models\Product;
use Modules\Warehouse\Models\ProductCategory;
use Modules\Warehouse\Models\Warehouse;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     path="/api/products/get-create-update-data",
 *     operationId="getProductCreateData",
 *     tags={"Warehouse > Products"},
 *     summary="Get product create data",
 *     description="Returns product create data data",
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation"
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
        $productCategories = ProductCategory::ForTenant($this->tenant->id)->select('id', 'name')->orderBy('parent_id')->get();
        $products = app(HttpRequestHandler::class)->get('https://dev.dasterang.ir/api/products/all');
        $warehouses = Warehouse::ForTenant($this->tenant->id)->get();
//        $accounts = AccountingDetailedAccount::ForTenant($this->tenant->id)->get();
        $code = Product::ForTenant($this->tenant?->id)->count() + 1;
        return Responder::success([
            'code' => $code,
            'product_categories' => $productCategories,
            'products' => is_array($products) && isset($products['data']['products']) ? $products['data']['products'] : [],
            'warehouses' => WarehouseResource::collection($warehouses),
//            'accounts' => AccountingDetailedAccountResource::collection($accounts),
            'accounts' => [],
            'types' => Product::getTranslatedTypes(),
            'statuses' => Product::getTranslatedStatuses(),
            'counting_units' => Product::getTranslatedCountingUnits()
        ]);
    }

    public function authorize()
    {
        return true;
    }
}
