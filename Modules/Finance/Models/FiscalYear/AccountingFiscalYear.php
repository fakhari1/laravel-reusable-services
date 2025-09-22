<?php

namespace Modules\Finance\Models\FiscalYear;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Central\Models\Traits\HasTenant;
use Modules\Finance\Models\FiscalYear\Traits\FiscalYearConstants;
use Modules\Finance\Models\FiscalYear\Traits\FiscalYearGetters;
use Modules\Tenancy\Models\Tenant;

class AccountingFiscalYear extends Model
{
    use SoftDeletes, HasTenant, FiscalYearGetters, FiscalYearConstants;

    protected $fillable = [
        'tenant_id',
        'title',
        'started_at',
        'finished_at',
        'status',
        'description',
        'deleted_at'
    ];

    protected $appends = [
        'duration_days',
    ];
}
