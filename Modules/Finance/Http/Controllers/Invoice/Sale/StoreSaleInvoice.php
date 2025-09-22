<?php

namespace Modules\Finance\Http\Controllers\Invoice\Sale;

use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Modules\Finance\Helpers\FinanceHelpers;
use Modules\Finance\Models\Account\AccountingDetailedAccount;
use Modules\Finance\Models\FiscalYear\AccountingFiscalYear;
use Modules\Service\Models\TenantProvidedService;
use Modules\Shared\Helpers\GlobalHelpers;
use Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Warehouse\Models\Product;
use OpenApi\Annotations as OA;

/**
 * @OA\Post(
 *     path="/api/workshops/accounting/invoices/sale-invoices/store",
 *     summary="Create a new sale invoice",
 *     operationId="storeSaleInvoice",
 *     tags={"Accounting > Invoices > SaleInvoices"},
 *          @OA\RequestBody(
 *          required=true,
 *          @OA\JsonContent(
 *              required={"date", "code", "invoice_items", "customer_account_id"},
 *              @OA\Property(property="date", type="string", example="1404/04/04"),
 *              @OA\Property(property="code", type="string", example="1001"),
 *              @OA\Property(property="customer_account_id", type="integer", example="1"),
 *              @OA\Property(property="discount_amount", type="integer", example="0"),
 *              @OA\Property(property="tax_amount", type="integer", example="0"),
 *              @OA\Property(property="description", type="string", example="this is test for sale invoice description", nullable=true),
 *              @OA\Property(property="invoice_items", type="array",
 *                   @OA\Items(type="object",
 *                       @OA\Property(property="id", type="integer",example="1"),
 *                       @OA\Property(property="invoice_itemable_id", type="integer",example="1"),
 *                       @OA\Property(property="invoice_itemable_type", type="string",example="Modules\Warehouse\Models\Product | Modules\Service\Models\TenantProvidedService"),
 *                       @OA\Property(property="unit", type="string", example="عدد"),
 *                       @OA\Property(property="unit_price", type="integer", example="15000"),
 *                       @OA\Property(property="count", type="integer", example="1")
 *                   )
 *              )
 *          ),
 *      ),
 *     @OA\Response(
 *         response=201,
 *         description="Document created successfully"
 *     )
 * )
 */
class StoreSaleInvoice extends BaseCrudHandler
{
    public function execute(array $attributes = [])
    {
        $attributes['date'] = GlobalHelpers::farsiToEnglishNumbers($attributes['date']);
        $fiscalYear = AccountingFiscalYear::ForTenant($this->tenant?->id)->where('status', AccountingFiscalYear::STATUS_ACTIVE)->first();
        $customerDetailedAccount = AccountingDetailedAccount::ForTenant($this->tenant->id)->where('id', $attributes['customer_account_id'])->first();
        $saleDetailedAccount = AccountingDetailedAccount::ForTenant($this->tenant->id)->where('slug', AccountingDetailedAccount::SLUG_SALE)->first();

        $invoiceFinalAmount = FinanceHelpers::calculateInvoiceFinalAmount($attributes, $attributes['discount_amount'], $attributes['tax_amount']);


    }

    public function authorize()
    {
        return true;
    }

    public function validate()
    {
        return [
            'code' => ['required', Rule::unique('accounting_sale_invoices', 'code')->where(function ($query) {
                $query->where('tenant_id', $this->tenant?->id);
            })],
            'date' => ['required'],
            'customer_account_id' => ['required', Rule::exists('accounting_detailed_accounts', 'id')->where(function ($query) {
                $query->where('tenant_id', $this->tenant?->id);
            })],
            'discount_amount' => ['required', 'numeric'],
            'tax_amount' => ['required', 'numeric'],
            'description' => ['nullable'],
            'invoice_items' => ['required', 'array'],
            'invoice_items.*.invoice_itemable_id' => ['required'],
            'invoice_items.*.invoice_itemable_type' => ['required'],
            'invoice_items.*.unit' => ['required'],
            'invoice_items.*.unit_price' => ['required', 'numeric', 'min:1'],
            'invoice_items.*.count' => ['required', 'numeric', 'min:1'],
        ];
    }

    public function afterValidator(array $attributes)
    {
        foreach ($attributes['invoice_items'] as $key => $item) {
            $rowNumber = $key + 1;

            if ($item['invoice_itemable_type'] == Product::class) {
                $product = Product::ForTenant($this->tenant?->id)->where('id', $item['invoice_itemable_id'])->first();

                if (!$product) {
                    throw ValidationException::withMessages([
                        "ردیف شماره {$rowNumber} کالا/خدمات نامعتبر وارد شده است"
                    ]);
                }
            }

            if ($item['invoice_itemable_type'] == TenantProvidedService::class) {
                $product = TenantProvidedService::ForTenant($this->tenant?->id)->where('id', $item['invoice_itemable_id'])->first();

                if (!$product) {
                    throw ValidationException::withMessages([
                        "ردیف شماره {$rowNumber} کالا/خدمات نامعتبر وارد شده است"
                    ]);
                }
            }
        }
    }
}
