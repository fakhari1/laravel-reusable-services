<?php

namespace Modules\Modules\Warehouse\Http\Controllers\Api\ProductCategory;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Modules\Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Modules\Shared\Services\Responder;
use Modules\Modules\Warehouse\Http\Resources\ProductCategory\ProductCategoryResource;
use Modules\Modules\Warehouse\Models\ProductCategory;
use OpenApi\Annotations as OA;

/**
 * @OA\Put(
 *     path="/api/product-categories/{id}/update",
 *     operationId="updateProductCategory",
 *     tags={"ProductCategories"},
 *     summary="Update existing product category",
 *     description="Returns updated product category data",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer"),
 *         description="ProductCategory ID"
 *     ),
 *          @OA\RequestBody(
 *          required=true,
 *         @OA\JsonContent(
 *              required={"parent_id", "name", "code", "status", "description"},
 *              @OA\Property(property="parent_id", type="integer", example=1),
 *              @OA\Property(property="name", type="string", example="product category 1"),
 *              @OA\Property(property="code", type="string", example="WH001"),
 *              @OA\Property(property="status", type="string", example="{active, inactive}"),
 *              @OA\Property(property="description", type="string", nullable=true, example="nullable"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(ref="#/components/schemas/ProductCategory")
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="Forbidden"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Product category not found"
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error"
 *     )
 * )
 */
class UpdateProductCategory extends BaseCrudHandler
{
    /**
     * Handle the incoming request.
     */
    public function execute(array $attributes = [])
    {
        $productCategory = ProductCategory::findOrFail($attributes['id']);

        $productCategory->update([
            'parent_id' => $attributes['parent_id'] ?? $productCategory->parent_id,
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
        $tenantId = $this->tenant?->id;

        return [
            'parent_id' => ['nullable', 'integer', 'exists:product_categories,id'],
            'code' => ['required', 'string', 'max:255', Rule::unique('product_categories', 'code')->where(function ($query) {
                $query->where('tenant_id', $this->tenant?->id);
            })->ignore($this->request->id)],
            'name' => ['required'],
            'status' => ['required'],
            'description' => 'nullable|string|max:1000'
        ];
    }
}
