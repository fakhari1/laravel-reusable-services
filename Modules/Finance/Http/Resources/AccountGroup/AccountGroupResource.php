<?php

namespace Modules\Finance\Http\Resources\AccountGroup;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Shared\Helpers\DateTimeHelpers;

class AccountGroupResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'nature' => [
                'key' => $this->nature,
                'value' => $this->nature
            ],
            'type' => [
                'key' => $this->type,
                'value' => $this->type,
            ],
            'created_at' => DateTimeHelpers::gregorianDateTimeToJalali($this->created_at?->format('Y-m-d H:i:s')),
            'children' => $this->when(
                !empty($this->generalAccounts),
                fn() => $this->generalAccounts
            ),
            'children_count' => $this->when(
                !empty($this->generalAccounts),
                fn() => $this->generalAccounts->count()
            ),
        ];


    }
}
