<?php

namespace Modules\Finance\Http\Resources\GeneralAccount;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Finance\Http\Resources\SpecificAccount\SpecificAccountResource;
use Modules\Shared\Helpers\DateTimeHelpers;

class GeneralAccountResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'url_prefix' => 'general',
            'group_id' => $this->group_id,
            'group' => [
                'id' => $this->group_id,
                'title' => $this->accountGroup?->title,
                'nature' => [
                    'key' => $this->accountGroup?->nature,
                    'value' => $this->accountGroup?->nature
                ],
                'type' => [
                    'key' => $this->accountGroup?->type,
                    'value' => $this->accountGroup?->type
                ]
            ],
            'code' => $this->code,
            'title' => $this->title,
            'label' => "{$this->code}-{$this->title}",
            'nature' => [
                'key' => $this->nature,
                'value' => $this->nature
            ],
            'status' => [
                'key' => $this->status,
                'value' => $this->status,
            ],
            'is_updatable' => true,
            'total_debit_amount' => $this->getTotalDebitAmount(),
            'total_credit_amount' => $this->getTotalCreditAmount(),
            'created_at' => DateTimeHelpers::gregorianDateTimeToJalali($this->created_at?->format('Y-m-d H:i:s')),
            'children' => $this->when(
                !empty($this->specificAccounts),
                fn() => SpecificAccountResource::collection($this->specificAccounts)
            ),
            'children_count' => $this->when(
                !empty($this->specificAccounts),
                fn() => $this->specificAccounts->count()
            )
        ];
    }
}
