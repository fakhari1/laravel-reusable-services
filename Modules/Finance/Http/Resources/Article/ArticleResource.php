<?php

namespace Modules\Finance\Http\Resources\Article;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Shared\Helpers\DateTimeHelpers;

class ArticleResource extends JsonResource
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
            'item_id' => $this->id,
            'document' => [
                'id' => $this->document_id,
                'date' => DateTimeHelpers::gregorianDateToJalali($this->document?->date),
                'code' => $this->document?->code,
            ],
            'detailed_account' => [
                'id' => $this->detailedAccount?->id,
                'title' => $this->detailedAccount?->title,
                'label' => $this->detailedAccount?->label,
                'code' => $this->detailedAccount?->code,
                'coding' => $this->detailedAccount?->coding,
            ],
            'debit_amount' => $this->debit_amount,
            'credit_amount' => $this->credit_amount,
            'description' => $this->description,
            'created_at' => DateTimeHelpers::gregorianDateTimeToJalali($this->created_at?->format('Y-m-d H:i:s')),
        ];
    }
}
