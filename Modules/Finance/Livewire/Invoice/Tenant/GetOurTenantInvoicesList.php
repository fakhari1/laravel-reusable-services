<?php

namespace Modules\Finance\Livewire\Invoice\Tenant;

use Illuminate\Filesystem\Filesystem;
use Livewire\Component;
use Modules\Finance\Models\Factor;
use Modules\RolePermission\Models\Permission;

class GetOurTenantInvoicesList extends Component
{
    public $start = '';
    public $end = '';
    public $order_id = '';
    public $tenant = null;
    public function mount()
    {
        $this->tenant = app('currentTenant');
    }

    public function render()
    {
//        $result = Factor::when($this->start, function ($q) {
//            $q->where('factors.created_at', '>=', $this->start);
//        })
//            ->when($this->end, function ($q) {
//                $q->where('factors.created_at', '<=', $this->end);
//            })
//            ->when($this->order_id, function ($q) {
//                $q->where('factors.order_id', $this->order_id);
//            })
//            ->when(!is_null($this->tenant), function ($q) {
//                $q->with('customer')->where(function ($q) {
//                    $q->whereHas('customer', function ($q) {
//                        $q->where('services_id', $this->tenant->id);
//                    })->orWhere('services_id', $this->tenant->id);
//                });
//            })
//            ->orderBy('id', 'desc')
//            ->paginate(10);

        return view('Finance::livewire.tenant.invoices.all', ['result' => $result])->layoutData(['page_name' => 'فاکتور های کارگاه خدماتی']);
    }
}
