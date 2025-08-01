<?php

namespace Modules\Modules\Warehouse\Http\Controllers\Api\Document\Transfer;

use Illuminate\Validation\Rule;
use Modules\Modules\Shared\Http\Controllers\BaseCrudHandler;
use OpenApi\Annotations as OA;

/**
 * @OA\Post(
 *     path="/api/warehouses/documents/transfer-docs/store",
 *     operationId="storeWarehouseTransferDocument",
 *     tags={"WarehouseDocuments"},
 *     summary="Store new warehouse transfer document",
 *     description="Returns warehouse transfer document data",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"warehouse_id", "deliverer_id", "receiver", "description"},
 *             @OA\Property(property="warehouse_id", type="integer", example=1),
 *             @OA\Property(property="deliverer_id", type="integer", example=1),
 *             @OA\Property(property="receiver", type="string", example="receiver name"),
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
 *         @OA\JsonContent(ref="#/components/schemas/Warehouse")
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error"
 *     )
 * )
 */
class StoreWarehouseTransferDocument extends BaseCrudHandler
{
    public function execute(array $attributes = [])
    {

    }

    public function validate()
    {
        return [
            'issuance_date' => ['required'],
            'code' => ['required', 'string', 'max:255', Rule::unique('warehouses', 'code')->where(function ($query) {
                $query->where('tenant_id', $this->tenant?->id);
            })],
            'type' => ['required', 'string', 'max:255'],
            'storekeeper_id' => ['required', 'exists:tenant_has_staff,id'],
            'address' => ['required', 'string'],
//            'province_id' => 'required|integer',
//            'city_id' => 'required|integer',
            'account_id' => ['required', 'integer'],
            'description' => ['nullable', 'string', 'max:1000']
        ];
    }
}
