<?php

namespace Modules\Finance\Http\Controllers\Invoice\Invoice;

use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Modules\Finance\Models\FiscalYear\AccountingFiscalYear;
use Modules\Service\Models\TenantProvidedService;
use Modules\Shared\Helpers\GlobalHelpers;
use Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Warehouse\Models\Product;
use OpenApi\Annotations as OA;

class StoreInvoice extends BaseCrudHandler
{
    public function execute(array $attributes = [])
    {
        $attributes['date'] = GlobalHelpers::farsiToEnglishNumbers($attributes['date']);

        $fiscalYear = AccountingFiscalYear::ForTenant($this->tenant?->id)->where('status', AccountingFiscalYear::STATUS_ACTIVE)->first();


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
            'items' => ['required', 'array'],
            'items.*.invoice_itemable_id' => ['required'],
            'items.*.invoice_itemable_type' => ['required'],
            'items.*.unit' => ['required'],
            'items.*.unit_price' => ['required', 'numeric', 'min:1'],
            'items.*.count' => ['required', 'numeric', 'min:1'],
        ];
    }

    public function afterValidator(array $attributes)
    {
        foreach ($attributes['items'] as $key => $item) {
            $rowNumber = $key + 1;

            if ($attributes['invoice_itemable_type'] == Product::class) {
                $product = Product::ForTenant($this->tenant?->id)->where('id', $attributes['invoice_itemable_id'])->first();

                if (!$product) {
                    throw ValidationException::withMessages([
                        "ردیف شماره {$rowNumber} کالا/خدمات نامعتبر وارد شده است"
                    ]);
                }
            }

            if ($attributes['invoice_itemable_type'] == TenantProvidedService::class) {
                $product = TenantProvidedService::ForTenant($this->tenant?->id)->where('id', $attributes['invoice_itemable_id'])->first();

                if (!$product) {
                    throw ValidationException::withMessages([
                        "ردیف شماره {$rowNumber} کالا/خدمات نامعتبر وارد شده است"
                    ]);
                }
            }
        }
    }
}
