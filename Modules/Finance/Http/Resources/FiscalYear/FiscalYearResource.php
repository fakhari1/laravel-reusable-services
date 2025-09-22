<?php

namespace Modules\Finance\Http\Resources\FiscalYear;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Finance\Models\FiscalYear\AccountingFiscalYear;
use Modules\Shared\Helpers\DateTimeHelpers;

class FiscalYearResource extends JsonResource
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
            'title' => $this->title,
            'started_at' => DateTimeHelpers::gregorianDateToJalali(Carbon::parse($this->started_at)->format('Y-m-d')),
            'finished_at' => DateTimeHelpers::gregorianDateToJalali(Carbon::parse($this->finished_at)->format('Y-m-d')),
            'status' => [
                'key' => $this->status,
                'value' => trans("container.{$this->status}")
            ],
            'description' => $this->description,
            'is_active' => $this->status == AccountingFiscalYear::STATUS_ACTIVE,
            'is_closed' => $this->status == AccountingFiscalYear::STATUS_CLOSED,
            'created_at' => DateTimeHelpers::gregorianDateTimeToJalali($this->created_at?->format('Y-m-d H:i:s')),
        ];
    }
}
