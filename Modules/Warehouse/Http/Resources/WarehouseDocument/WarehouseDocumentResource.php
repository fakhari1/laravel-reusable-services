<?php

namespace Modules\Warehouse\Http\Resources\WarehouseDocument;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Identity\Http\Resources\StaffResource;
use Modules\Shared\Helpers\DateTimeHelpers;
use Modules\Warehouse\Models\WarehouseDocument;
use Modules\Warehouse\Models\WarehouseDocumentProduct;

class WarehouseDocumentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'warehouse_id' => $this->warehouse_id,
            'warehouse' => [
                'id' => $this->warehouse->id,
                'code' => $this->warehouse->code,
                'name' => $this->warehouse->name
            ],
            'type' => [
                'key' => $this->type,
                'value' => trans("container.{$this->type}")
            ],
            'document' => [
                'receiver' => $this->type == WarehouseDocument::TYPE_RECEIPT ? new StaffResource($this->documentable?->receiver) : $this->documentable->receiver,
                'deliverer' => $this->type == WarehouseDocument::TYPE_TRANSFER ? new StaffResource($this->documentable?->deliverer) : $this->documentable?->deliverer
            ],
            'delivery_type' => $this->delivery_type,
            'status' => trans("container.{$this->status}"),
            'description' => $this->description,
            'created_at' => DateTimeHelpers::gregorianDateTimeToJalali($this->created_at?->format('Y-m-d H:i:s')),
            'date' => DateTimeHelpers::gregorianDateTimeToJalali($this->date?->format('Y-m-d H:i:s')),
            'products' => WarehouseDocumentProduct::where('warehouse_document_id', $this->id)->with('product', 'rack')->get(),
            'products_count' => WarehouseDocumentProduct::where('warehouse_document_id', $this->id)->count(),
        ];
    }
}
