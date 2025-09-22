<?php

namespace Modules\Warehouse\Http\Controllers\Api\Document;

use Modules\Finance\Http\Resources\AccountingDetailedAccountResource;
use Modules\Identity\Http\Resources\CustomerResource;
use Modules\Identity\Http\Resources\StaffResource;
use Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Shared\Services\Responder;
use Modules\Warehouse\Http\Resources\ProductCategory\ProductCategoryResource;
use Modules\Warehouse\Http\Resources\Warehouse\WarehouseResource;
use Modules\Warehouse\Models\ProductCategory;
use Modules\Warehouse\Models\WarehouseDocument;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     path="/api/warehouses/documents/get-create-update-data",
 *     operationId="getWarehouseDocumentCreateUpdateData",
 *     tags={"Warehouse > Documents"},
 *     summary="Get warehouse document create and update data",
 *     description="Returns warehouse document create and update data data",
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="Forbidden"
 *     ),
 * )
 */
class GetWarehouseDocumentCreateUpdateData extends BaseCrudHandler
{
    /**
     * Handle the incoming request.
     */
    public function handle(array $attributes = [])
    {
        $staff = $this->tenant->staff;
        $customers = $this->tenant->tenantCustomers;
//        $accountingDetailedAccounts = AccountingDetailedAccount::ForTenant($this->tenant->id)->doesntHave('children')->get();
        $productCategories = ProductCategory::ForTenant($this->tenant?->id)->whereHas('products')->get();


        return Responder::success([
            'delivery_types' => [
                [
                    'key' => 'خرید و فروش',
                    'value' => 'خرید و فروش'
                ],
                [
                    'key' => 'امانی',
                    'value' => 'امانی'
                ],
            ],
            'product_categories' => ProductCategoryResource::collection($productCategories),
            'code' => WarehouseDocument::ForTenant($this->tenant?->id)->count() + 1,
            'warehouses' => WarehouseResource::collection($this->tenant?->warehouses),
            'staff' => StaffResource::collection($staff),
            'customers' => CustomerResource::collection($customers),
            'default_storekeeper' => new StaffResource(auth('api-tenant')->user()),
//            'accounts' => AccountingDetailedAccountResource::collection($accountingDetailedAccounts)
            'accounts' => []
        ]);
    }

    public function authorize()
    {
        return true;
    }
}
