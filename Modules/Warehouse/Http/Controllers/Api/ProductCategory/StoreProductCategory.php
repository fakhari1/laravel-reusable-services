<?php

namespace Modules\Warehouse\Http\Controllers\Api\ProductCategory;

use Illuminate\Validation\Rule;
use Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Shared\Services\Responder;
use Modules\Warehouse\Http\Resources\ProductCategory\ProductCategoryResource;
use Modules\Warehouse\Models\ProductCategory;
use OpenApi\Annotations as OA;

/**
 * @OA\Post(
 *     path="/api/product-categories/store",
 *     summary="Create a new product category",
 *     operationId="storeProductCategory",
 *     tags={"Warehouse > ProductCategories"},
 *     security={{"bearer_token":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *              required={"parent_id", "name", "code", "status", "description"},
 *              @OA\Property(property="parent_id", type="integer", example=1),
 *              @OA\Property(property="name", type="string", example="product 1"),
 *              @OA\Property(property="code", type="string", example="WH001"),
 *              @OA\Property(property="status", type="string", example="{active, inactive}"),
 *              @OA\Property(property="description", type="string", nullable=true,example="nullable"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Product category created successfully",
 *         @OA\JsonContent(
 *             allOf={
 *                 @OA\Schema(
 *                     @OA\Property(property="data")
 *                 )
 *             }
 *         )
 *     )
 * )
 */
class StoreProductCategory extends BaseCrudHandler
{
    /**
     * Handle the incoming request.
     */
    public function execute(array $attributes = [])
    {
        $productCategory = ProductCategory::create([
            'tenant_id' => $this->tenant?->id,
            'parent_id' => $attributes['parent_id'] ?? null,
            'name' => $attributes['name'],
            'code' => $attributes['code'],
            'status' => $attributes['status'],
            'description' => $attributes['description'] ?? null,
        ]);

        return Responder::success([
            'product_category' => new ProductCategoryResource($productCategory)
        ]);
    }

    public function validate()
    {
        return [
            'parent_id' => ['nullable', 'integer', Rule::exists('product_categories', 'id')->where(function ($query) {
                $query->where('tenant_id', $this->tenant?->id);
            })],
            'code' => ['required', Rule::unique('product_categories', 'code')->where(function ($query) {
                $query->where('tenant_id', $this->tenant?->id);
            })],
            'name' => ['required'],
            'status' => ['required'],
            'description' => ['nullable', 'string', 'max:1000']
        ];
    }

    public function authorize()
    {
        return true;
    }
}
