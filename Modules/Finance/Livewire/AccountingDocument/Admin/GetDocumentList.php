<?php

namespace Modules\Finance\Livewire\AccountingDocument\Admin;

use Livewire\Component;
use Modules\Project\Models\Order;
use Modules\RolePermission\Models\Permission;
use function App\Helpers\saveUser;

class GetDocumentList extends Component
{
    public $services_id;
    public $order_id;

    public function mount()
    {

        if (!auth('admin')->user()->hasAnyPermission([
            Permission::PERMISSION_MANAGING_FINANCIAL_AFFAIRS,
            Permission::PERMISSION_MANAGING_ALL_TENANTS_DOCUMENTS,
            Permission::PERMISSION_VIEW_ALL_TENANTS_DOCUMENTS,
            Permission::PERMISSION_VIEW_OUR_DOCUMENTS,
            Permission::PERMISSION_MANAGING_OUR_DOCUMENTS,
        ])) {
            abort(403);
        }
        $user_id = saveUser(null, null, 'services_id_return');
        $this->services_id = $user_id;

    }

    public function render()
    {
        if (auth('admin')->check() || auth()->user()->can('secretary')) {
            $orders = Order::with('user', 'services')->where('status', 'send')->where('status_services', '!=', 'ارسال شده (بایگانی سفارش)')->orderBy('updated_at', 'desc')->get();

        } elseif (auth()->user()->can('manager_order')) {
            $orders = Order::with('user', 'services')->where('status', 'send')->where('status_services', '!=', 'ارسال شده (بایگانی سفارش)')->where('services_id', auth()->user()->parent)->latest()->get();

        } else {
            $orders = Order::with('user', 'services')->where('status', 'send')->where('status_services', '!=', 'ارسال شده (بایگانی سفارش)')->where('services_id', auth()->user()->id)->latest()->get();
        }

        $data = ['orders' => $orders];
        return view('livewire.admin.pages.financial.document.index', $data)->layoutData(['page_name' => 'document_index']);
    }
}
