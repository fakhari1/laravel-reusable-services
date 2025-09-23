<?php

namespace Modules\Finance\Http\Resources\Invoice\InvoiceItemable;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Finance\Http\Resources\DetailedAccount\DetailedAccountResource;
use Modules\Shared\Helpers\DateTimeHelpers;
use Modules\Warehouse\Http\Resources\WarehouseProduct\WarehouseProductResource;
use Modules\Warehouse\Models\Product;
use Modules\Warehouse\Models\WarehouseProduct;

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
            'title' => $this->invoiceItemableIsProduct() ? $this->name : $this->title,
            'warehouses' => isset($this->warehouseProducts) ? WarehouseProductResource::collection($this->warehouseProducts) : [],
            'main_counting_unit' => $this->main_counting_unit ?? $this->unit,
            'sub_counting_unit' => $this->sub_counting_unit ?? $this->unit,
        ];
    }

    public function invoiceItemableIsProduct()
    {
        return $this->invoiceItemableType == Product::class;
    }
}
