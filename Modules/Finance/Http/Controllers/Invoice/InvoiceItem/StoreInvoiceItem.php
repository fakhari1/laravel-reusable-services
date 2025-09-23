<?php

namespace Modules\Finance\Http\Controllers\Invoice\InvoiceItem;

use Illuminate\Validation\Rule;
use Modules\Finance\Models\Invoice\AccountingInvoiceItem;
use Modules\Shared\Http\Controllers\AsStaticRunner;
use Modules\Shared\Http\Controllers\BaseCrudHandler;

class StoreInvoiceItem extends BaseCrudHandler
{
    use AsStaticRunner;
    public function execute(array $attributes = [])
    {
        $createdInvoiceItem = AccountingInvoiceItem::create([
            'tenant_id' => $this->tenant?->id,
            'fiscal_year_id' => $attributes['fiscal_year_id'],
            'invoice_id' => $attributes['invoice_id'],
            'invoice_itemable_id' => $attributes['invoice_itemable_id'],
            'invoice_itemable_type' => $attributes['invoice_itemable_type'],
            'unit' => $attributes['unit'],
            'unit_price' => $attributes['unit_price'],
            'count' => $attributes['count'],
            'total_price' => $attributes['unit_price'] * $attributes['count'],
        ]);

        return $createdInvoiceItem;
    }

    public function authorize()
    {
        return true;
    }

    public function validate()
    {
        return [
            'fiscal_year_id' => ['required', Rule::exists('accounting_fiscal_years', 'id')->where(function ($query) {
                $query->where('tenant_id', $this->tenant?->id);
            })],
            'invoice_id' => ['required', Rule::exists('accounting_invoices', 'id')->where(function ($query) {
                $query->where('tenant_id', $this->tenant?->id);
            })],
            'invoice_itemable_id' => ['required'],
            'invoice_itemable_type' => ['required'],
            'unit' => ['required'],
            'unit_price' => ['required'],
            'count' => ['required'],
        ];
    }
}
