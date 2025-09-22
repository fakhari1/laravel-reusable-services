<?php

namespace Modules\Finance\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Finance\Models\FiscalYear\AccountingFiscalYear;
use Modules\Shared\Helpers\DateTimeHelpers;
use Modules\Tenancy\Models\Tenant;

class FiscalYearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Tenant::all()->each(function ($tenant) {
            AccountingFiscalYear::create([
                'tenant_id' => $tenant->id,
                'title' => 'سال مالی 1404',
                'started_at' => DateTimeHelpers::jalaliDateToGregorian('1404/01/01'),
                'finished_at' => DateTimeHelpers::jalaliDateToGregorian('1404/12/29'),
                'status' => AccountingFiscalYear::STATUS_ACTIVE
            ]);

            AccountingFiscalYear::create([
                'tenant_id' => $tenant->id,
                'title' => 'سال مالی 1403',
                'started_at' => DateTimeHelpers::jalaliDateToGregorian('1403/01/01'),
                'finished_at' => DateTimeHelpers::jalaliDateToGregorian('1403/12/30'),
                'status' => AccountingFiscalYear::STATUS_CLOSED
            ]);
        });
    }
}
