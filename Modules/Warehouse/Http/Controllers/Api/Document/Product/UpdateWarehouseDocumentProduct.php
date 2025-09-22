<?php

namespace Modules\Warehouse\Http\Controllers\Api\Document\Product;

use Illuminate\Validation\Rule;
use Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Shared\Services\Responder;
use Modules\Warehouse\Http\Resources\WarehouseDocument\WarehouseDocumentResource;
use Modules\Warehouse\Models\Product;
use Modules\Warehouse\Models\WarehouseDocument;
use Modules\Warehouse\Models\WarehouseDocumentProduct;
use Modules\Warehouse\Models\WarehouseProduct;
use OpenApi\Annotations as OA;


/**
 * @OA\Put(
 *     path="/api/warehouses/documents/products/{id}/update",
 *     operationId="updateWarehouesDocumentProduct",
 *     tags={"Warehouse > Documents"},
 *     summary="Update existing warehouse document product",
 *     description="Returns updated warehouse document product data",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer"),
 *         description="WarehouseDocumentProduct ID"
 *     ),
 *          @OA\RequestBody(
 *          required=true,
 *          @OA\JsonContent(
 *                    @OA\Property(property="warehouse_id", type="integer",example=100),
 *                    @OA\Property(property="rack_id", type="integer",example=100),
 *                    @OA\Property(property="product_id", type="integer", example=100),
 *                    @OA\Property(property="unit", type="string"),
 *                    @OA\Property(property="count", type="numeric", example=100),
 *          )
 *      ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
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
class UpdateWarehouseDocumentProduct extends BaseCrudHandler
{
    public function execute(array $attributes = [])
    {
        $warehouseDocumentProduct = WarehouseDocumentProduct::findOrFail($attributes['id']);

        $product = Product::find($warehouseDocumentProduct->product_id);

        $warehouseHasProduct = WarehouseProduct::where('warehouse_id', $warehouseDocumentProduct->warehouseDocument->warehouse_id)
            ->where('product_id', $warehouseDocumentProduct->product_id)
            ->first();

        $quantity = $warehouseHasProduct->quantity;
        $warehouseDocumentType = $warehouseDocumentProduct->warehouseDocument->type;

        if ($warehouseDocumentType == WarehouseDocument::TYPE_RECEIPT) {
            if ($product->main_counting_unit == $attributes['unit']) {
                $quantity -= $warehouseDocumentProduct->count;
                $quantity += $attributes['count'];
            } else {
                $quantity -= (ceil($warehouseDocumentProduct->count / $product->coefficient * (10 ** 2)) / (10 ** 2));
                // ceil 1.43435345 to 1.44 not 2 integer
                $quantity += (ceil($attributes['count'] / $product->coefficient * (10 ** 2)) / (10 ** 2));
            }
        } else {
            if ($product->main_counting_unit == $attributes['unit']) {
                $quantity += $warehouseDocumentProduct->count;
                $quantity -= $attributes['count'];
            } else {
                $quantity += (ceil($warehouseDocumentProduct->count / $product->coefficient * (10 ** 2)) / (10 ** 2));
                // ceil 1.43435345 to 1.44 not 2 integer
                $quantity -= (ceil($attributes['count'] / $product->coefficient * (10 ** 2)) / (10 ** 2));
            }
        }

        $warehouseDocumentProduct->update([
            'rack_id' => $attributes['rack_id'],
            'unit' => $attributes['unit'],
            'count' => $attributes['count'],
        ]);

        $warehouseHasProduct->update([
            'quantity' => $quantity,
        ]);

        return Responder::success([
            'warehouse_document_product' => $warehouseDocumentProduct
        ]);
    }

    public function validate()
    {
        $tenantId = $this->tenant->id;

        return [
            'rack_id' => ['required', Rule::exists('warehouse_racks', 'id')->where(function ($query) use ($tenantId) {
                $query->where('tenant_id', $tenantId)->where('warehouse_id', $this->request->warehouse_id);
            })],
            'product_id' => ['required', Rule::exists('products', 'id')->where(function ($query) use ($tenantId) {
                $query->where('tenant_id', $tenantId);
            })],
            'unit' => ['required'],
            'count' => ['required', 'numeric'],
        ];
    }

    public function authorize()
    {
        return true;
    }
}
