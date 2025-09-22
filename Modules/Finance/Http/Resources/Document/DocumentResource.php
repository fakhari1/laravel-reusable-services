<?php

namespace Modules\Finance\Http\Resources\Document;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Finance\Http\Resources\Article\ArticleResource;
use Modules\Shared\Helpers\DateTimeHelpers;

class DocumentResource extends JsonResource
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
            'fiscal_year_id' => $this->fiscal_year_id,
            'fiscal_year' => [
                'id' => $this->fiscal_year_id,
                'title' => $this->fiscalYear?->title
            ],
            'code' => $this->code,
            'date' => DateTimeHelpers::gregorianDateTimeToJalali(Carbon::parse($this->date)->format('Y-m-d')),
            'creator_id' => $this->creator_id,
            'creator' => [
                'id' => $this->creator_id,
                'full_name' => $this->creator->full_name,
            ],
            'articles' => $this->when(
                !empty($this->articles),
                fn() => ArticleResource::collection($this->articles)
            ),
            'articles_count' => $this->when(
                !empty($this->articles),
                fn() => $this->articles->count()
            ),
            'status' => [
                'key' => $this->status,
                'value' => $this->status
            ],
            'description' => $this->description,
            'created_at' => DateTimeHelpers::gregorianDateTimeToJalali($this->created_at?->format('Y-m-d H:i:s')),
        ];
    }
}
