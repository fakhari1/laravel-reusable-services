<?php

namespace Modules\Finance\Http\Controllers\Invoice\Purchase;

use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Modules\Finance\Helpers\FinanceHelpers;
use Modules\Finance\Http\Controllers\Document\StoreAccountingDocument;
use Modules\Finance\Http\Controllers\Invoice\Invoice\StoreInvoice;
use Modules\Finance\Http\Controllers\Invoice\Sale\InvoiceResource;
use Modules\Finance\Models\Account\AccountingDetailedAccount;
use Modules\Finance\Models\Document\AccountingDocument;
use Modules\Finance\Models\FiscalYear\AccountingFiscalYear;
use Modules\Finance\Models\Invoice\AccountingInvoice;
use Modules\Finance\Models\Invoice\AccountingSaleInvoice;
use Modules\Service\Models\TenantProvidedService;
use Modules\Shared\Helpers\DateTimeHelpers;
use Modules\Shared\Helpers\GlobalHelpers;
use Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Shared\Services\Responder;
use Modules\Warehouse\Http\Controllers\Api\Document\Product\StoreWarehouseDocumentProduct;
use Modules\Warehouse\Http\Controllers\Api\Document\Transfer\StoreWarehouseTransferDocument;
use Modules\Warehouse\Models\Product;
use Modules\Warehouse\Models\WarehouseDocument;
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
class StorePurchaseInvoice extends BaseCrudHandler
{
    public function execute(array $attributes = [])
    {
        $attributes['date'] = GlobalHelpers::farsiToEnglishNumbers($attributes['date']);
        $fiscalYear = AccountingFiscalYear::ForTenant($this->tenant?->id)->where('status', AccountingFiscalYear::STATUS_ACTIVE)->first();
        $customerDetailedAccount = AccountingDetailedAccount::ForTenant($this->tenant->id)->where('id', $attributes['customer_account_id'])->first();
        $saleDetailedAccount = AccountingDetailedAccount::ForTenant($this->tenant->id)->where('slug', AccountingDetailedAccount::SLUG_SALE)->first();

        $invoiceCalculatedAmount = FinanceHelpers::calculateInvoiceFinalAmount($attributes, $attributes['discount_amount'], $attributes['tax_amount']);

        if ($attributes['discount_amount'] > 0) {
            $attributes['discount_account_id'] = AccountingDetailedAccount::ForTenant($this->tenant->id)
                ->where('slug', AccountingDetailedAccount::SLUG_SALE_DISCOUNT)
                ->first()
                ->id;
        }

        if ($attributes['tax_amount'] > 0) {
            $attributes['value_added_account_id'] = AccountingDetailedAccount::ForTenant($this->tenant->id)
                ->where('slug', AccountingDetailedAccount::SLUG_SALE_VALUE_ADDED_TAX)
                ->first()
                ->id;
        }

        $createdSaleInvoice = AccountingSaleInvoice::create([
            'tenant_id' => $this->tenant?->id,
            'fiscal_year_id' => $fiscalYear->id,
            'code' => $attributes['code'],
            'customer_account_id' => $customerDetailedAccount->id,
            'sale_account_id' => $saleDetailedAccount->id,
            'value_added_account_id' => $attributes['value_added_account_id'] ?? null,
            'discount_account_id' => $attributes['discount_account_id'] ?? null,
            'customer_id' => $customerDetailedAccount->assignable_id,
            'description' => $attributes['description'] ?? null
        ]);

        $createdInvoice = StoreInvoice::run([
            'tenant_id' => $this->tenant->id,
            'fiscal_year_id' => $fiscalYear->id,
            'creator_id' => auth('api-tenant')->user()->id,
            'date' => DateTimeHelpers::jalaliDateToGregorian($attributes['date']),
            'total_amount' => $invoiceCalculatedAmount['total_amount'],
            'discount_amount' => $attributes['discount_amount'],
            'tax_amount' => $attributes['tax_amount'],
            'final_amount' => $invoiceCalculatedAmount['final_amount'],
            'invoiceable_id' => $createdSaleInvoice->id,
            'invoiceable_type' => get_class($createdSaleInvoice),
            'type' => AccountingInvoice::TYPE_SALE,
            'description' => $attributes['description'] ?? null,
            'items' => $attributes['invoice_items'],
        ]);

        $accountingDocument = $this->createAccountingDocumentForSaleInvoice($attributes, $createdSaleInvoice, $invoiceCalculatedAmount);
        $warehouseDocument = $this->createWarehouseDocumentForSaleInvoice($attributes, $createdSaleInvoice, $invoiceCalculatedAmount);

        $invoice = AccountingInvoice::findOrFail($createdInvoice->id);

        $invoice->update([
            'accounting_document_id' => $accountingDocument->id,
            'warehouse_document_id' => $warehouseDocument->id
        ]);

        return Responder::success([
            'invoice' => new InvoiceResource($createdInvoice),
        ]);
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

    private function createAccountingDocumentForSaleInvoice($attributes, $createdSaleInvoice, $invoiceCalculatedAmount)
    {
        $code = AccountingDocument::ForTenant($this->tenant->id)->latest()->first()->code + 1;
        $articleDescription = 'بابت فاکتور فروش ' . $createdSaleInvoice->code;

        $articles[] = [
            'description' => $articleDescription,
            'detailed_account_id' => $createdSaleInvoice->customer_account_id,
            'debit_amount' => $invoiceCalculatedAmount,
            'credit_amount' => 0
        ];

        $articles[] = [
            'description' => $articleDescription,
            'detailed_account_id' => $createdSaleInvoice->sale_account_id,
            'debit_amount' => 0,
            'credit_amount' => $invoiceCalculatedAmount,
        ];

        if (!is_null($createdSaleInvoice->value_added_account_id)) {
            $articles[] = [
                'description' => $articleDescription,
                'detailed_account_id' => $createdSaleInvoice->value_added_account_id,
                'debit_amount' => 0,
                'credit_amount' => $invoiceCalculatedAmount,
            ];
        }

        if (!is_null($createdSaleInvoice->discount_account_id)) {
            $articles[] = [
                'description' => $articleDescription,
                'detailed_account_id' => $createdSaleInvoice->discount_account_id,
                'debit_amount' => $invoiceCalculatedAmount,
                'credit_amount' => 0,
            ];
        }

        $document = StoreAccountingDocument::run([
            'code' => $code,
            'date' => $attributes['date'],
            'description' => 'بابت فاکتور فروش',
            'articles' => $articles
        ]);

        return $document;
    }

    private function createWarehouseDocumentForSaleInvoice(array $attributes, $createdSaleInvoice, array $invoiceCalculatedAmount)
    {
        $code = WarehouseDocument::ForTenant($this->tenant?->id)->count() + 1;

        $products = [];
        $warehousesIds = [];

        foreach ($attributes['invoice_items'] as $key => $invoiceItem) {
            if (!in_array($invoiceItem['warehouse_id'], $warehousesIds) && !is_null($invoiceItem['warehouse_id'])) {
                $warehousesIds[$key] = $invoiceItem['warehouse_id'];
            }
        }

        foreach ($attributes['invoice_items'] as $key => $invoiceItem) {
            $products[] = array_filter($invoiceItem, function ($item) use ($warehousesIds) {
                if (in_array($item['warehouse_id'], $warehousesIds)) {
                    return $item;
                }
            });
        }

        $document = StoreWarehouseTransferDocument::run([
            'date' => $attributes['date'],
            'code' => $code,
            'deliverer_id' => auth('api-tenant')->user()->id,
            'receiver_id' => $attributes['customer_id'],
            'products' => $products,
            'description' => 'بابت فاکتور فروش ' . $createdSaleInvoice->code,
        ]);

        return $document;
    }
}
