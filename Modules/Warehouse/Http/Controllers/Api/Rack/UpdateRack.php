<?php

namespace Modules\Modules\Warehouse\Http\Controllers\Api\Rack;

use App\Models\Rack;
use Illuminate\Http\Request;
use Modules\Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Modules\Shared\Services\Responder;
use Modules\Modules\Warehouse\Http\Resources\Rack\RackResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Put(
 *     path="/api/warehouses/racks/{id}/update",
 *     operationId="updateRack",
 *     tags={"Racks"},
 *     summary="Update existing rack",
 *     description="Returns updated rack data",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer"),
 *         description="Rack ID"
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name", "description"},
 *             @OA\Property(property="name", type="string", example="rack 1"),
 *             @OA\Property(property="description", type="string", example="nullable", nullable=true)
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
class UpdateRack extends BaseCrudHandler
{
    /**
     * Handle the incoming request.
     */
    public function execute(array $attributes = [])
    {
        $rack = Rack::where('id', $attributes['id'])->firstOrFail();

        $rack->update([
            'name' => $attributes['name'],
            'description' => $attributes['description'] ?? $rack->description,
        ]);

        return Responder::success([
            'rack' => new RackResource($rack)
        ]);
    }
}
