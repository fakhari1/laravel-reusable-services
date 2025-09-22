<?php

namespace Modules\Finance\Http\Resources\SpecificAccount;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Finance\Http\Resources\DetailedAccount\DetailedAccountResource;
use Modules\Shared\Helpers\DateTimeHelpers;

class SpecificAccountResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'url_prefix' => 'specific',
            'general_account_id' => $this->general_account_id,
            'general_account' => [
                'id' => $this->general_account_id,
                'title' => $this->generalAccount?->title,
                'nature' => [
                    'key' => $this->generalAccount?->nature,
                    'value' => $this->generalAccount?->nature
                ],
            ],
            'code' => $this->code,
            'title' => $this->title,
            'label' => "{$this->coding}-{$this->title}",
            'status' => [
                'key' => $this->status,
                'value' => $this->status,
            ],
            'nature' => [
                'key' => $this->nature,
                'value' => $this->nature
            ],
            'total_debit_amount' => $this->getTotalDebitAmount(),
            'total_credit_amount' => $this->getTotalCreditAmount(),
            'is_updatable' => true,
            'created_at' => DateTimeHelpers::gregorianDateTimeToJalali($this->created_at?->format('Y-m-d H:i:s')),
            'children' => $this->when(
                !empty($this->detailedAccounts()->whereNotNull('parent_id')->get()),
                fn() => DetailedAccountResource::collection($this->detailedAccounts()->whereNotNull('parent_id')->get())
            ),
            'children_count' => $this->when(
                !empty($this->detailedAccounts()->whereNotNull('parent_id')->get()),
                fn() => $this->detailedAccounts()->whereNotNull('parent_id')->count()
            )
        ];
    }
}
