<?php

namespace Modules\Modules\Warehouse\Http\Controllers\Api\Warehouse;

use Illuminate\Validation\Rule;
use Modules\Identity\Http\Controllers\Address\StoreAddress;
use Modules\Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Modules\Shared\Services\Responder;
use Modules\Modules\Warehouse\Http\Controllers\Api\Rack\StoreRack;
use Modules\Modules\Warehouse\Http\Resources\Warehouse\WarehouseResource;
use Modules\Modules\Warehouse\Models\Warehouse;
use OpenApi\Annotations as OA;
use function Modules\Warehouse\Http\Controllers\Api\Warehouse\auth;

/**
 * @OA\Post(
 *     path="/api/warehouses/store",
 *     operationId="storeWarehouse",
 *     tags={"Warehouses"},
 *     summary="Store new warehouse",
 *     description="Returns warehouse data",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"code","type","storekeeper_id","account_id", "province_id", "city_id", "address"},
 *             @OA\Property(property="code", type="string", example="WH001"),
 *             @OA\Property(property="name", type="string", example="warehouse 1"),
 *             @OA\Property(property="type", type="string", example="nullable", nullable=true),
 *             @OA\Property(property="storekeeper_id", type="integer", example=1, nullable=true),
 *             @OA\Property(property="address", type="string", example="nullable"),
 *             @OA\Property(property="account_id", type="integer", example=1, nullable=true),
 *             @OA\Property(property="description", type="string", example="nullable", nullable=true),
 *             @OA\Property(property="racks", type="array",
 *                  @OA\Items(type="object",
 *                      @OA\Property(property="name", type="string",example="100"),
 *                      @OA\Property(property="description", type="string", example="nullable", nullable=true),
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
class StoreWarehouse extends BaseCrudHandler
{
    public function execute(array $attributes = [])
    {
        $address = null;

        if (!empty($attributes['address']))
            $address = StoreAddress::run([
                'province_id' => $attributes['province_id'] ?? $this->tenant->state_id,
                'city_id' => $attributes['city_id'] ?? $this->tenant->city_id,
                'address' => $attributes['address']
            ]);

        $warehouse = Warehouse::create([
            'tenant_id' => $this->tenant?->id ?? auth('api-tenant')->user()->tenant->id,
            'address_id' => $address?->id ?? null,
            'name' => $attributes['name'] ?? null,
            'code' => $attributes['code'] ?? null,
            'type' => $attributes['type'] ?? null,
            'storekeeper_id' => $attributes['storekeeper_id'] ?? null,
            'account_id' => $attributes['account_id'] ?? null,
            'description' => $attributes['description'] ?? null,
        ]);

        $rackCode = 1000;

        if (isset($attributes['racks']))
            foreach ($attributes['racks'] as $rack) {
                StoreRack::run([
                    'warehouse_id' => $warehouse->id,
                    'name' => $rack['name'],
                    'code' => $rack['code'] ?? $rackCode,
                    'description' => $rack['description'] ?? null,
                ]);
                ++$rackCode;
            }

        return Responder::success([
            'warehouse' => new WarehouseResource($warehouse),
        ]);
    }

    public function validate()
    {
        return [
            'name' => ['required'],
            'code' => ['required', 'string', 'max:255', Rule::unique('warehouses', 'code')->where(function ($query) {
                $query->where('tenant_id', $this->tenant?->id);
            })],
            'type' => 'nullable|string|max:255',
            'storekeeper_id' => ['required', 'exists:tenant_has_staff,id'],
            'address' => 'nullable|string',
//            'province_id' => 'required|integer',
//            'city_id' => 'required|integer',
            'account_id' => 'nullable|integer',
            'description' => 'nullable|string|max:1000'
        ];
    }
}
