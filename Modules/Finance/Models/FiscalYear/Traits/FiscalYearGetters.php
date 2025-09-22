<?php

namespace Modules\Finance\Models\FiscalYear\Traits;

use Carbon\Carbon;

trait FiscalYearGetters
{
    private function getDurationDaysAttribute()
    {
        if (!$this->started_at || !$this->finished_at) {
            return null;
        }

        try {
            $start = Carbon::parse($this->started_at);
            $end = Carbon::parse($this->finished_at);
            return $start->diffInDays($end);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public static function getTranslatedStatuses(): array
    {
        $result = [];

        foreach (self::$statuses as $key => $status) {
            $result[] = [
                'key' => $status,
                'value' => $status
            ];
        }

        return $result;
    }
}
