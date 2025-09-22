<?php

namespace Modules\Warehouse\Http\Controllers\Api\Document\Product;

use Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Shared\Services\Responder;
use Modules\Warehouse\Models\Product;
use Modules\Warehouse\Models\WarehouseDocument;
use Modules\Warehouse\Models\WarehouseDocumentProduct;
use OpenApi\Annotations as OA;

/**
 * @OA\Delete(
 *     path="/api/warehouses/documents/products/{id}/delete",
 *     operationId="deleteWarehouseDocumentProduct",
 *     tags={"Warehouse > Documents"},
 *     summary="Delete existing warehouse document product",
 *     description="Deletes a record and returns no content",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer"),
 *         description="WarehouseDocumentProduct ID"
 *     ),
 *     @OA\Response(
 *         response=204,
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
class DestroyWarehouseDocumentProduct extends BaseCrudHandler
{
    public function execute(array $attributes = [])
    {
        $warehouseDocumentProduct = WarehouseDocumentProduct::where('id', $attributes['id'])->findOrFail();
        $warehouseDocumentType = $warehouseDocumentProduct->warehouseDocument->type;
        $selectedProduct = Product::find($warehouseDocumentProduct->product_id);
        $quantity = $selectedProduct->quantity;

        if ($warehouseDocumentType == WarehouseDocument::TYPE_RECEIPT) {
            if ($selectedProduct->hasSameCountingUnits()) {
                $quantity -= ($warehouseDocumentProduct->count / $selectedProduct->coefficient);
            } else {
                $quantity -= $warehouseDocumentProduct->count;
            }
        } else {
            if ($selectedProduct->hasSameCountingUnits()) {
                $quantity += ($warehouseDocumentProduct->count / $selectedProduct->cofficient);
            } else {
                $quantity += $warehouseDocumentProduct->count;
            }
        }

        $selectedProduct->update([
            'quantity' => $quantity,
        ]);

        return Responder::success($warehouseDocumentProduct->delete());
    }

    public function authorize()
    {
        return true;
    }
}
