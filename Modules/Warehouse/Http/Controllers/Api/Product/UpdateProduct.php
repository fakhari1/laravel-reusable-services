<?php

namespace Modules\Warehouse\Http\Controllers\Api\Product;

use Illuminate\Validation\Rule;
use Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Shared\Services\Responder;
use Modules\Warehouse\Http\Resources\Product\ProductResource;
use Modules\Warehouse\Http\Resources\WarehouseProduct\WarehouseProductResource;
use Modules\Warehouse\Models\Product;
use Modules\Warehouse\Models\WarehouseProduct;
use OpenApi\Annotations as OA;

/**
 * @OA\Put(
 *     path="/api/products/{id}/update",
 *     operationId="updateProduct",
 *     tags={"Warehouse > Products"},
 *     summary="Update existing product",
 *     description="Returns updated product data",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer"),
 *         description="Product ID"
 *     ),
 *          @OA\RequestBody(
 *          required=true,
 *          @OA\JsonContent(
 *              required={"dasterang_product_id", "code", "name", "product_category_id", "main_counting_unit", "beginning_inventory", "coefficient", "sub_counting_unit", "status", "type", "description"},
 *              @OA\Property(property="dasterang_product_id", type="integer", example="11800", nullable=true),
 *              @OA\Property(property="code", type="string", example="PROD001"),
 *              @OA\Property(property="name", type="string", example="Laptop"),
 *              @OA\Property(property="product_category_id", type="integer", example=1),
 *              @OA\Property(property="main_counting_unit", type="string", example="piece"),
 *              @OA\Property(property="beginning_inventory", type="integer", example=100),
 *              @OA\Property(property="coefficient", type="number", format="float", example=1.50),
 *              @OA\Property(property="sub_counting_unit", type="string", example="piece"),
 *              @OA\Property(property="status", type="string", example="{active, inactive}"),
 *              @OA\Property(property="type", type="array", @OA\Items(type="string"), example={"type 1", "type 2", "type 3"}),
 *              @OA\Property(property="description", type="string", example="nullable")
 *          )
 *      ),
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
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error"
 *     )
 * )
 */
class UpdateProduct extends BaseCrudHandler
{
    public function execute(array $attributes = [])
    {
        $product = Product::where('id', $attributes['id'])->firstOrFail();

        $currentBeginningInventory = $product->beginning_inventory;

        $product->update([
            'dasterang_product_id' => $attributes['dasterang_product_id'] ?? null,
            'code' => $attributes['code'],
            'product_category_id' => $attributes['product_category_id'],
            'name' => $attributes['name'],
            'beginning_inventory' => $attributes['beginning_inventory'],
            'main_counting_unit' => $attributes['main_counting_unit'],
            'sub_counting_unit' => $attributes['sub_counting_unit'],
            'coefficient' => $attributes['coefficient'] ?? 1,
            'status' => $attributes['status'],
            'type' => $attributes['type'],
            'description' => $attributes['description'] ?? null,
//            'account_id' => $attributes['account_id'],
        ]);

//        if ($currentBeginningInventory != $attributes['beginning_inventory']) {
            $product->fresh()->computeTotalQuantities();
//        }

        return Responder::success([
            'product' => new ProductResource($product)
        ]);
    }

    public function validate()
    {
        $tenantId = $this->tenant?->id;

        return [
            'dasterang_product_id' => ['nullable', 'integer'],
            'name' => ['required'],
            'product_category_id' => ['required', Rule::exists('product_categories', 'id')->where(function ($query) use ($tenantId) {
                $query->where('tenant_id', $tenantId);
            })],
            'code' => ['required', Rule::unique('products', 'code')
                ->where(function ($query) use ($tenantId) {
                    return $query->where('tenant_id', $tenantId);
                })->ignore($this->request->id)],
            'main_counting_unit' => ['required', Rule::in(Product::$countingUnits)],
            'sub_counting_unit' => ['required', Rule::in(Product::$countingUnits)],
            'beginning_inventory' => ['required', 'numeric'],
            'coefficient' => ['required', 'numeric'],
            'status' => ['required'],
            'type' => ['required', 'array'],
            'type.*' => ['required', 'string'],
//            'warehouse_id' => ['required', Rule::exists('warehouses', 'id')->where(function ($query) use ($tenantId) {
//                $query->where('tenant_id', $tenantId);
//            })],
//            'rack_id' => ['required'],
            'description' => ['nullable', 'string', 'max:1000'],
            'account_id' => ['nullable', /*'exists:accounting_detailed_accounts,id'*/]
        ];
    }

    public function authorize()
    {
        return true;
    }
}
