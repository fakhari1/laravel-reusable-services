<?php

namespace Modules\Finance\Http\Resources\DetailedAccount;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Shared\Helpers\DateTimeHelpers;

class DetailedAccountResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'url_prefix' => 'detailed',
            'specific_account_id' => $this->specific_account_id,
            'specific_account' => [
                'id' => $this->specific_account_id,
                'code' => $this->specificAccount?->code,
                'title' => $this->specificAccount?->title,
            ],
            'parent_id' => $this->parent_id,
            'parent' => [
                'id' => $this->parent?->id,
                'code' => $this->parent?->code,
                'title' => $this->parent?->title
            ],
            'code' => $this->code,
            'title' => $this->title,
            'label' => "{$this->coding}-{$this->title}",
            'nature' => $this->nature,
            'status' => [
                'key' => $this->status,
                'value' => $this->status,
            ],
            'debit_amount' => $this->debit_amount,
            'credit_amount' => $this->credit_amount,
            'total_debit_amount' => $this->getTotalDebitAmount(),
            'total_credit_amount' => $this->getTotalCreditAmount(),
//            'subject' => $this->subject,
            'is_updatable' => true,
            'created_at' => DateTimeHelpers::gregorianDateTimeToJalali($this->created_at?->format('Y-m-d H:i:s')),
            'children' => $this->when(
                !empty($this->children),
                fn() => DetailedAccountResource::collection($this->children)
            ),
            'children_count' => $this->when(
                !empty($this->children),
                fn() => $this->children->count()),
        ];
    }
}
