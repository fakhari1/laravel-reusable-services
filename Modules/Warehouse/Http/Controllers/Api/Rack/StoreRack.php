<?php

namespace Modules\Modules\Warehouse\Http\Controllers\Api\Rack;

use Modules\Identity\Models\Address;
use Modules\Modules\Shared\Http\Controllers\AsStaticRunner;
use Modules\Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Modules\Shared\Services\Responder;
use Modules\Modules\Warehouse\Http\Resources\Rack\RackResource;
use Modules\Modules\Warehouse\Http\Resources\Warehouse\WarehouseResource;
use Modules\Modules\Warehouse\Models\Rack;
use Modules\Modules\Warehouse\Models\Warehouse;
use OpenApi\Annotations as OA;
use function Modules\Warehouse\Http\Controllers\Api\Rack\auth;

/**
 * @OA\Post(
 *     path="/api/warehouses/{id}/racks/store",
 *     operationId="storeRack",
 *     tags={"Racks"},
 *     summary="Store rack(s) for warehouse",
 *     description="Returns warehouse data",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name", "description"},
 *             @OA\Property(property="name", type="string", example="rack 23"),
 *             @OA\Property(property="description", type="string", example="nullable", nullable=true),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Successful operation",
 *         @OA\JsonContent(ref="#/components/schemas/Rack")
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error"
 *     )
 * )
 */
class StoreRack extends BaseCrudHandler
{
    use AsStaticRunner;

    public function execute(array $attributes = [])
    {
        if (isset($attributes['racks'])) {
            foreach ($attributes['racks'] as $rack) {

                Rack::create([
                    'warehouse_id' => $attributes['id'],
                    'name' => $rack['name'],
                    'status' => Rack::STATUS_ACTIVE,
                    'description' => $rack['description'] ?? null,
                    'tenant_id' => auth('api-tenant')->user()->tenant?->id,
                ]);

            }

            return Responder::success([
                'warehouse' => new WarehouseResource(Warehouse::findOrFail($attributes['id'])),
            ]);

        } else {

            $rack = Rack::create([
                'warehouse_id' => $attributes['warehouse_id'],
                'name' => $attributes['name'],
                'status' => Rack::STATUS_ACTIVE,
                'description' => $attributes['description'] ?? null,
                'tenant_id' => auth('api-tenant')->user()->tenant?->id,
            ]);

            return Responder::success(new RackResource($rack));
        }
    }
}
