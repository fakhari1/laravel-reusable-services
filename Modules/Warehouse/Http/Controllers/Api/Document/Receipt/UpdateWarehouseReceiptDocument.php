<?php

namespace Modules\Warehouse\Http\Controllers\Api\Document\Receipt;

use Illuminate\Validation\Rule;
use Modules\Shared\Helpers\DateTimeHelpers;
use Modules\Shared\Helpers\GlobalHelpers;
use Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Shared\Services\Responder;
use Modules\Warehouse\Http\Resources\WarehouseDocument\WarehouseDocumentResource;
use Modules\Warehouse\Models\WarehouseDocument;
use OpenApi\Annotations as OA;

/**
 * @OA\Put(
 *     path="/api/warehouses/documents/receipt-docs/{id}/update",
 *     operationId="updateWarehouseReceiptDocument",
 *     tags={"Warehouse > Documents"},
 *     summary="Update existing warehouse receipt document",
 *     description="Returns updated warehouse receipt document data",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer"),
 *         description="WarehouseDocument ID"
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"warehouse_id", "deliverer", "receiver_id", "description"},
 *             @OA\Property(property="warehouse_id", type="integer", example=1),
 *             @OA\Property(property="receiver_id", type="integer", example=1),
 *             @OA\Property(property="deliverer_id", type="string", example=1),
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
 *         response=200,
 *         description="Successful operation",
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="Forbidden"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Warehouse not found"
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error"
 *     )
 * )
 */

class UpdateWarehouseReceiptDocument extends BaseCrudHandler
{
    public function execute(array $attributes = [])
    {
        $attributes['date'] = GlobalHelpers::farsiToEnglishNumbers($attributes['date']);

        $document = WarehouseDocument::findOrFail($attributes['id']);

        $document->update([
            'warehouse_id' => $attributes['warehouse_id'],
            'code' => $attributes['code'],
            'status' => $attributes['status'] ?? $document->status,
            'delivery_type' => $attributes['delivery_type'],
            'description' => $attributes['description'] ?? null,
            'date' => DateTimeHelpers::jalaliDateTimeToGregorian($attributes['date']),
        ]);

        $document->documentable?->update([
            'warehouse_id' => $attributes['warehouse_id'],
            'receiver_id' => $attributes['receiver_id'],
            'deliverer_id' => $attributes['deliverer_id'],
        ]);

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
            })->ignore($this->request->id)],
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
