<?php

namespace Modules\Finance\Http\Controllers\Invoice\Invoice;

use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Modules\Finance\Http\Controllers\Invoice\InvoiceItem\StoreInvoiceItem;
use Modules\Finance\Models\FiscalYear\AccountingFiscalYear;
use Modules\Finance\Models\Invoice\AccountingInvoice;
use Modules\Service\Models\TenantProvidedService;
use Modules\Shared\Helpers\GlobalHelpers;
use Modules\Shared\Http\Controllers\AsStaticRunner;
use Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Warehouse\Models\Product;
use OpenApi\Annotations as OA;

class StoreInvoice extends BaseCrudHandler
{
    use AsStaticRunner;

    public function execute(array $attributes = [])
    {
        $createdInvoice = AccountingInvoice::create([
            'tenant_id' => $this->tenant?->id,
            'fiscal_year_id' => $attributes['fiscal_year_id'],
            'creator_id' => $attributes['creator_id'],
            'date' => $attributes['date'],
            'total_amount' => $attributes['total_amount'],
            'discount_amount' => $attributes['discount_amount'],
            'tax_amount' => $attributes['tax_amount'],
            'final_amount' => $attributes['final_amount'],
            'invoiceable_id' => $attributes['invoiceable_id'],
            'invoiceable_type' => $attributes['invoiceable_type'],
            'type' => $attributes['type'],
            'description' => $attributes['description'] ?? null
        ]);

        foreach ($attributes['items'] as $key => $item) {
            StoreInvoiceItem::run([
                'fiscal_year_id' => $attributes['fiscal_year_id'],
                'invoice_id' => $createdInvoice->id,
                'invoice_itemable_id' => $item['invoice_itemable_id'],
                'invoice_itemable_type' => $item['invoice_itemable_type'],
                'unit' => $item['unit'],
                'unit_price' => $item['unit_price'],
                'count' => $item['count'],
            ]);
        }

        return $createdInvoice->fresh();

    }

    public function authorize()
    {
        return true;
    }

    public function validate()
    {
        return [
            'date' => ['required'],
            'fiscal_year_id' => ['required', Rule::exists('accounting_fiscal_years', 'id')->where(function ($query) {
                $query->where('tenant_id', $this->tenant?->id);
            })],
            'creator_id' => ['required', Rule::exists('tenant_has_staff', 'id')->where(function ($query) {
                $query->where('tenant_id', $this->tenant?->id);
            })],
            'total_amount' => ['required', 'numeric'],
            'discount_amount' => ['nullable', 'numeric'],
            'tax_amount' => ['nullable', 'numeric'],
            'final_amount' => ['required', 'numeric'],
            'invoiceable_id' => ['required'],
            'invoiceable_type' => ['required'],
            'type' => ['required'],
            'description' => ['nullable'],
            'items' => ['required', 'array'],
            'items.*.invoice_itemable_id' => ['required'],
            'items.*.invoice_itemable_type' => ['required'],
            'items.*.unit' => ['required'],
            'items.*.unit_price' => ['required', 'numeric', 'min:1'],
            'items.*.count' => ['required', 'numeric', 'min:1'],
        ];
    }
}
