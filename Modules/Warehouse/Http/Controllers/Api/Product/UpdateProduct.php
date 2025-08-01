<?php

namespace Modules\Modules\Warehouse\Http\Controllers\Api\Product;

use Illuminate\Validation\Rule;
use Modules\Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Modules\Shared\Services\Responder;
use Modules\Modules\Warehouse\Http\Resources\Product\ProductResource;
use Modules\Modules\Warehouse\Models\Product;
use OpenApi\Annotations as OA;

/**
 * @OA\Put(
 *     path="/api/products/{id}/update",
 *     operationId="updateProduct",
 *     tags={"Products"},
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
 *              required={"dasterang_product_id", "code", "name", "product_category_id", "main_counting_unit", "stock_count", "coefficient", "sub_counting_unit", "status", "type", "description"},
 *              @OA\Property(property="dasterang_product_id", type="integer", example="11800"),
 *              @OA\Property(property="code", type="string", example="PROD001"),
 *              @OA\Property(property="name", type="string", example="Laptop"),
 *              @OA\Property(property="product_category_id", type="integer", example=1),
 *              @OA\Property(property="main_counting_unit", type="string", example="piece"),
 *              @OA\Property(property="stock_count", type="integer", example=100),
 *              @OA\Property(property="coefficient", type="number", format="float", example=1.50),
 *              @OA\Property(property="sub_counting_unit", type="string", example="piece"),
 *              @OA\Property(property="status", type="string", example="{active, inactive}"),
 *              @OA\Property(property="type", type="string", example=""),
 *              @OA\Property(property="description", type="string", example="nullable")
 *          )
 *      ),
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

        $product->update([
            'dasterang_product_id' => $attributes['dasterang_product_id'],
            'code' => $attributes['code'],
            'product_category_id' => $attributes['product_category_id'],
            'name' => $attributes['name'],
            'main_counting_unit' => $attributes['main_counting_unit'],
            'stock_count' => $attributes['stock_count'],
            'coefficient' => $attributes['coefficient'],
            'sub_counting_unit' => $attributes['sub_counting_unit'],
            'status' => $attributes['status'],
            'type' => $attributes['type'],
            'description' => $attributes['description'] ?? null,
            'account_id' => $attributes['account_id'],
        ]);

        return Responder::success([
            'product' => new ProductResource($product)
        ]);
    }

    public function validate()
    {
        $tenantId = $this->tenant?->id;

        return [
            'dasterang_product_id' => ['required', 'integer'],
            'name' => ['required'],
            'product_category_id' => ['required', 'exists:product_categories,id'],
            'code' => ['required', Rule::unique('products', 'code')->where(function ($query) use ($tenantId) {
                return $query->where('tenant_id', $tenantId);
            })->ignore($this->request->id)],
            'main_counting_unit' => ['required'],
            'sub_counting_unit' => ['required'],
            'stock_count' => ['required', 'integer'],
            'coefficient' => ['required', 'numeric'],
            'status' => ['required'],
            'type' => ['required', 'string', 'max:255'],
            'warehouse_id' => ['required', Rule::exists('warehouses', 'id')->where(function ($query) use ($tenantId) {
                $query->where('tenant_id', $tenantId);
            })],
            'storekeeper_id' => ['required', Rule::exists('tenant_has_staff', 'id')->where(function ($query) use ($tenantId) {
                $query->where('tenant_id', $tenantId);
            })],
            'description' => ['nullable', 'string', 'max:1000'],
            'account_id' => ['required', /*'exists:accounting_detailed_accounts,id'*/]
        ];
    }
}
