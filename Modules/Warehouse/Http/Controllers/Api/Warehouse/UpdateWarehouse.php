<?php

namespace Modules\Modules\Warehouse\Http\Controllers\Api\Warehouse;

use Illuminate\Validation\Rule;
use Modules\Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Modules\Shared\Services\Responder;
use Modules\Modules\Warehouse\Http\Resources\Warehouse\WarehouseResource;
use Modules\Modules\Warehouse\Models\Warehouse;
use Modules\Warehouse\Http\Resources\Warehouse\WarehouseDocumentResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Put(
 *     path="/api/warehouses/{id}/update",
 *     operationId="updateWarehouse",
 *     tags={"Warehouses"},
 *     summary="Update existing warehouse",
 *     description="Returns updated warehouse data",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer"),
 *         description="Warehouse ID"
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"code","type","storekeeper_id","address","account_id","province_id","city_id"},
 *             @OA\Property(property="code", type="string", example="WH001"),
 *             @OA\Property(property="name", type="string", example="warehouse 1"),
 *             @OA\Property(property="type", type="string", example="main"),
 *             @OA\Property(property="storekeeper_id", type="integer", example=1),
 *             @OA\Property(property="province_id", type="integer", example=1),
 *             @OA\Property(property="city_id", type="integer", example=1),
 *             @OA\Property(property="address", type="string", example=1),
 *             @OA\Property(property="account_id", type="integer", example=1),
 *             @OA\Property(property="description", type="string", example="nullable")
 *         ),
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(ref="#/components/schemas/Warehouse")
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
class UpdateWarehouse extends BaseCrudHandler
{
    public function execute(array $attributes = [])
    {
        $warehouse = Warehouse::findOrFail($attributes['id']);

        if (isset($attributes['address'])) {
            $warehouse->address()->update([
//            'province_id' => $attributes['province_id'],
//            'city_id' => $attributes['city_id'],
                'text' => $attributes['address'],
            ]);
        }

        $warehouse->update([
            'name' => $attributes['name'],
            'code' => $attributes['code'],
            'type' => $attributes['type'] ?? null,
            'storekeeper_id' => $attributes['storekeeper_id'],
            'account_id' => $attributes['account_id'] ?? null,
            'description' => $attributes['description'] ?? $warehouse->description,
        ]);

        return Responder::success([
            'warehouse' => new WarehouseResource($warehouse)
        ]);
    }

    public function validate()
    {
        $tenantId = $this->tenant?->id;

        return [
            'name' => ['required'],
            'code' => ['required', 'string', 'max:255', Rule::unique('warehouses', 'code')->where(function ($query) use ($tenantId) {
                $query->where('tenant_id', $tenantId);
            })->ignore($this->request->id)],
            'type' => ['nullable', 'string', 'max:255'],
            'storekeeper_id' => ['required', 'integer', Rule::exists('tenant_has_staff', 'id')->where(function ($query) use ($tenantId) {
                $query->where('tenant_id', $tenantId);
            })],
//            'province_id' => 'required|integer',
//            'city_id' => 'required|integer',
            'address' => ['nullable', 'string'],
            'account_id' => ['nullable', 'integer'],
            'description' => ['nullable', 'string', 'max:1000']
        ];
    }
}
