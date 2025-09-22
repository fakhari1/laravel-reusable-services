<?php

namespace Modules\Finance\Http\Resources\Invoice\InvoiceItemable;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Finance\Http\Resources\DetailedAccount\DetailedAccountResource;
use Modules\Shared\Helpers\DateTimeHelpers;
use Modules\Warehouse\Models\Product;

class InvoiceItemableResource extends JsonResource
{
    public $invoiceItemableType = null;

    public function toArray($request)
    {
        $this->invoiceItemableType = get_class($this->resource);

        return [
            'invoice_itemable_id' => $this->id,
            'invoice_itemable_type' => $this->invoiceItemableType,
            'code' => $this->invoiceItemableIsProduct() ? $this->code : '-',
            'title' => $this->invoiceItemableType == Product::class ? $this->name : $this->title,
            'main_counting_unit' => $this->main_counting_unit ?? $this->unit,
            'sub_counting_unit' => $this->sub_counting_unit ?? $this->unit,
        ];
    }

    public function invoiceItemableIsProduct()
    {
        return $this->invoiceItemableType == Product::class;
    }
}
