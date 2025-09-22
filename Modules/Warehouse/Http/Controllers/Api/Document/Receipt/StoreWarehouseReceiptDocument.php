<?php

namespace Modules\Warehouse\Http\Controllers\Api\Document\Receipt;

use Illuminate\Validation\Rule;
use Modules\Shared\Helpers\DateTimeHelpers;
use Modules\Shared\Helpers\GlobalHelpers;
use Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Shared\Services\Responder;
use Modules\Warehouse\Http\Resources\WarehouseDocument\WarehouseDocumentResource;
use Modules\Warehouse\Models\Product;
use Modules\Warehouse\Models\WarehouseDocument;
use Modules\Warehouse\Models\WarehouseReceiptDocument;
use OpenApi\Annotations as OA;

/**
 * @OA\Post(
 *     path="/api/warehouses/documents/receipt-docs/store",
 *     operationId="storeWarehouseReceiptDocument",
 *     tags={"Warehouse > Documents"},
 *     summary="Store new warehouse receipt document",
 *     description="Returns warehouse receipt document data",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"warehouse_id", "deliverer", "receiver_id", "description"},
 *             @OA\Property(property="warehouse_id", type="integer", example=1),
 *             @OA\Property(property="receiver_id", type="integer", example=1),
 *             @OA\Property(property="deliverer_id", type="integer", example=1),
 *             @OA\Property(property="description", type="string", example="nullable", nullable=true),
 *             @OA\Property(property="products", type="array",
 *                  @OA\Items(type="object",
 *                      @OA\Property(property="rack_id", type="integer",example=100),
 *                      @OA\Property(property="product_id", type="integer", example=100),
 *                      @OA\Property(property="unit", type="string"),
 *                      @OA\Property(property="count", type="integer", example=100),
 *                  )
 *             )
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
class StoreWarehouseReceiptDocument extends BaseCrudHandler
{
    public function execute(array $attributes = [])
    {
        $attributes['date'] = GlobalHelpers::farsiToEnglishNumbers($attributes['date']);

        $documentable = WarehouseReceiptDocument::create([
            'warehouse_id' => $attributes['warehouse_id'],
            'receiver_id' => $attributes['receiver_id'],
            'deliverer_id' => $attributes['deliverer_id'],
        ]);

        $document = WarehouseDocument::create([
            'tenant_id' => $this->tenant?->id,
            'staff_id' => auth('api-tenant')->id(),
            'warehouse_id' => $attributes['warehouse_id'],
            'type' => WarehouseDocument::TYPE_RECEIPT,
            'code' => $attributes['code'],
            'documentable_id' => $documentable->id,
            'documentable_type' => WarehouseReceiptDocument::class,
            'delivery_type' => $attributes['delivery_type'],
            'status' => WarehouseDocument::STATUS_APPROVED,
            'description' => $attributes['description'] ?? null,
            'date' => DateTimeHelpers::jalaliDateTimeToGregorian($attributes['date']),
        ]);

        foreach ($attributes['products'] as $key => $product) {

            $selectedProduct = Product::where('id', $product['product_id'])
                ->whereHas('warehouseProducts', function ($warehouseProduct) use ($attributes) {
                    $warehouseProduct->where('warehouse_id', $attributes['warehouse_id']);
                })
                ->first();

            if ($selectedProduct) {
                $warehouseHasProduct = $selectedProduct->warehouseProducts()->where('warehouse_id', $attributes['warehouse_id'])->first();

                $warehouseHasProduct->computeQuantity($product['count'], $product['unit'], WarehouseDocument::TYPE_RECEIPT);

                $document->products()->attach($product['product_id'], [
                    'rack_id' => $product['rack_id'],
                    'unit' => $product['unit'],
                    'count' => $product['count'],
                ]);
            } else {
                $existsProduct = Product::where('id', $product['product_id'])->first();

                $existsProduct->warehouseProducts()->create([
                    'tenant_id' => $this->tenant?->id,
                    'warehouse_id' => $attributes['warehouse_id'],
                    'rack_id' => $product['rack_id'] ?? null,
                    'quantity' => $product['count'],
                ]);

                $document->products()->attach($product['product_id'], [
                    'rack_id' => $product['rack_id'],
                    'unit' => $product['unit'],
                    'count' => $product['count'],
                ]);
            }
        }

        return Responder::success([
            'warehouse_document' => new WarehouseDocumentResource($document)
        ]);
    }

    public function validate()
    {
        return [
            'warehouse_id' => ['required', Rule::exists('warehouses', 'id')->where(function ($query) {
                $query->where('tenant_id', $this->tenant?->id);
            })],
            'date' => ['required'],
            'code' => ['required', Rule::unique('warehouse_documents', 'code')->where(function ($query) {
                $query->where('tenant_id', $this->tenant?->id);
            })],
//            'products_category_id' => ['required', Rule::exists('product_categories', 'id')->where(function ($query) {
//                $query->where('tenant_id', $this->tenant?->id);
//            })],
            'products' => ['required', 'array'],
            'products.*.rack_id' => ['required', Rule::exists('warehouse_racks', 'id')->where(function ($query) {
                $query->where('tenant_id', $this->tenant?->id);
            })],
            'products.*.product_id' => ['required', Rule::exists('products', 'id')->where(function ($query) {
                $query->where('tenant_id', $this->tenant?->id);
            })],
            'products.*.unit' => ['required'],
            'products.*.count' => ['required', 'numeric'],
            'receiver_id' => ['required', Rule::exists('tenant_has_staff', 'id')->where(function ($query) {
                $query->where('tenant_id', $this->tenant?->id);
            })],
            'deliverer_id' => ['required', Rule::exists('tenant_has_customers', 'id')->where(function ($query) {
                $query->where('tenant_id', $this->tenant?->id);
            })],
            'delivery_type' => ['required'],
            'description' => ['nullable']
        ];
    }

    public function authorize()
    {
        return true;
    }
}
