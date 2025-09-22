<?php

namespace Modules\Warehouse\Http\Controllers\Api\Document\Product;

use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Shared\Services\Responder;
use Modules\Warehouse\Http\Resources\WarehouseDocument\WarehouseDocumentResource;
use Modules\Warehouse\Models\Product;
use Modules\Warehouse\Models\WarehouseDocument;
use Modules\Warehouse\Models\WarehouseProduct;
use Modules\Warehouse\Models\WarehouseTransferDocument;
use OpenApi\Annotations as OA;

/**
 * @OA\Post(
 *     path="/api/warehouses/documents/{id}/products/store",
 *     operationId="storeWarehouseDocumentProduct",
 *     tags={"Warehouse > Documents"},
 *     summary="Store new warehouse document product",
 *     description="Returns warehouse document product data",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *                      @OA\Property(property="warehouse_id", type="integer",example=100),
 *                      @OA\Property(property="rack_id", type="integer",example=100),
 *                      @OA\Property(property="product_id", type="integer", example=100),
 *                      @OA\Property(property="unit", type="string"),
 *                      @OA\Property(property="count", type="integer", example=100),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Successful operation",
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error"
 *     )
 * )
 */
class StoreWarehouseDocumentProduct extends BaseCrudHandler
{
    public function execute(array $attributes = [])
    {
        $document = WarehouseDocument::findOrFail($attributes['id']);
        $product = Product::where('id', $attributes['product_id'])->first();

        $document->products()->attach($attributes['product_id'], [
            'rack_id' => $attributes['rack_id'],
            'unit' => $attributes['unit'],
            'count' => $attributes['count'],
        ]);

        $warehouseHasProduct = WarehouseProduct::where('product_id', $attributes['product_id'])
            ->where('warehouse_id', $document->warehouse_id)
            ->first();

        $quantity = $warehouseHasProduct->quantity;
        if ($document->type == WarehouseDocument::TYPE_RECEIPT) {
            if ($product->main_counting_unit == $attributes['unit']) {
                $quantity += $attributes['count'];
            } else {
                // ceil 1.43435345 to 1.44 not 2 integer
                $quantity += (ceil($attributes['count'] / $product->coefficient * (10 ** 2)) / (10 ** 2));
            }
        } else {
            if ($product->main_counting_unit == $attributes['unit']) {
                $quantity -= $attributes['count'];
            } else {
                // ceil 1.43435345 to 1.44 not 2 integer
                $quantity -= (ceil($attributes['count'] / $product->coefficient * (10 ** 2)) / (10 ** 2));
            }
        }

        $warehouseHasProduct->update([
            'quantity' => $quantity,
        ]);

        return Responder::success([
            'warehouse_document' => new WarehouseDocumentResource($document)
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

