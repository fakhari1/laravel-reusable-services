<?php

namespace Modules\Warehouse\Http\Controllers\Api\Product;

use Illuminate\Validation\Rule;
use Modules\Shared\Http\Controllers\AsStaticRunner;
use Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Shared\Services\Responder;
use Modules\Warehouse\Http\Resources\Product\ProductResource;
use Modules\Warehouse\Http\Resources\WarehouseProduct\WarehouseProductResource;
use Modules\Warehouse\Models\Product;
use OpenApi\Annotations as OA;

/**
 * @OA\Post(
 *     path="/api/products/store",
 *     summary="Create a new product",
 *     operationId="storeProduct",
 *     tags={"Warehouse > Products"},
 *     security={{"bearer_token":{}}},
 *     @OA\RequestBody(
 *         @OA\JsonContent(
 *             required={"dasterang_product_id", "code", "name", "product_category_id", "main_counting_unit", "beginning_inventory", "coefficient", "status", "type", "description"},
 *             @OA\Property(property="dasterang_product_id", type="integer", example="11800"),
 *             @OA\Property(property="code", type="string", example="PROD001"),
 *             @OA\Property(property="name", type="string", example="Laptop"),
 *             @OA\Property(property="product_category_id", type="integer", example=1),
 *             @OA\Property(property="warehouse_id", type="integer", example=1),
 *             @OA\Property(property="rack_id", type="integer", example=1),
 *             @OA\Property(property="main_counting_unit", type="string", example="piece"),
 *             @OA\Property(property="beginning_inventory", type="numeric", example=100),
 *             @OA\Property(property="coefficient", type="number", format="float", example=1.50),
 *             @OA\Property(property="sub_counting_unit", type="string", example="piece"),
 *             @OA\Property(property="status", type="string", example="{active, inactive}"),
 *             @OA\Property(property="type", type="array", @OA\Items(type="string"), example={"type 1", "type 2", "type 3"}),
 *             @OA\Property(property="description", type="string", example="nullable")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Product created successfully"
 *     )
 * )
 */
class StoreProduct extends BaseCrudHandler
{
    use AsStaticRunner;

    public function execute(array $attributes = [])
    {
        $product = Product::create([
            'tenant_id' => $attributes['tenant_id'] ?? $this->tenant?->id,
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

        if (!is_null($attributes['warehouse_id']) && !is_null($attributes['rack_id'])) {
            $warehouseHasProduct = $product->warehouseProducts()->create([
                'tenant_id' => $this->tenant?->id,
                'warehouse_id' => $attributes['warehouse_id'],
                'rack_id' => $attributes['rack_id'] ?? null,
                'quantity' => $attributes['quantity'] ?? $attributes['beginning_inventory'],
            ]);

            return Responder::success([
                'product' => new WarehouseProductResource($warehouseHasProduct)
            ]);
        }

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
            'product_category_id' => ['required', Rule::exists('product_categories', 'id')->where(function ($query) {
                $query->where('tenant_id', $this->tenant?->id);
            })],
            'code' => ['required', Rule::unique('products', 'code')
                ->where(function ($query) use ($tenantId) {
                    return $query->where('tenant_id', $tenantId);
                })],
            'main_counting_unit' => ['required', Rule::in(Product::$countingUnits)],
            'sub_counting_unit' => ['required', Rule::in(Product::$countingUnits)],
            'beginning_inventory' => [Rule::requiredIf(!is_null($this->request->warehouse_id)), 'numeric'],
            'coefficient' => ['required', 'numeric'],
            'status' => ['required'],
            'type' => ['required', 'array'],
            'type.*' => ['required', 'string'],
            'warehouse_id' => ['nullable', Rule::requiredIf($this->request->beginning_inventory > 0), Rule::exists('warehouses', 'id')->where(function ($query) use ($tenantId) {
                $query->where('tenant_id', $tenantId);
            })],
            'rack_id' => ['nullable', Rule::requiredIf(!is_null($this->request->warehouse_id)), Rule::exists('warehouse_racks', 'id')->where(function ($query) {
                $query->where('tenant_id', $this->tenant?->id);
            })],
            'description' => ['nullable', 'string', 'max:1000'],
            'account_id' => ['nullable', /*'exists:accounting_detailed_accounts,id'*/]
        ];
    }

    public function authorize()
    {
        return true;
    }
}
