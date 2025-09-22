<?php

namespace Modules\Warehouse\Http\Controllers\Api\Product;

use Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Shared\Services\HttpRequestHandler;
use Modules\Shared\Services\Responder;
use Modules\Warehouse\Http\Resources\Warehouse\WarehouseDocumentCollection;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     path="/api/cutunix/products/all",
 *     operationId="getCutunixProductsList",
 *     tags={"Warehouse > Products"},
 *     summary="Get list of products in cutunix portal",
 *     description="Returns list of products of cutunix portal",
 *     @OA\Parameter(
 *         name="search",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string"),
 *         description="Search in name and code of products"
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="data",
 *             ),
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized"
 *     )
 * )
 */
class GetCutunixProductsList extends BaseCrudHandler
{
    public function handle(array $attributes = [])
    {
        $productsRequestResponse = app(HttpRequestHandler::class)->get('https://dev.dasterang.ir/api/products/all', [
            'search' => $attributes['search'] ?? null,
        ]);

        return Responder::success([
            'products' => $productsRequestResponse['data']['products']
        ]);
    }

    public function authorize()
    {
        return true;
    }
}
