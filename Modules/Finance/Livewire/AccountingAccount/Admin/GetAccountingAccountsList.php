<?php

namespace Modules\Finance\Livewire\AccountingAccount\Admin;

use Livewire\Component;
use Modules\RolePermission\Models\Permission;
use function App\Helpers\saveUser;

class GetAccountingAccountsList extends Component
{
    public $services_id;

    public function mount()
    {

        if (!auth('admin')->user()->hasAnyPermission([
            Permission::PERMISSION_MANAGING_FINANCIAL_AFFAIRS,
            Permission::PERMISSION_MANAGING_ALL_TENANTS_ACCOUNTING_ACCOUNTS,
            Permission::PERMISSION_VIEW_ALL_TENANTS_ACCOUNTING_ACCOUNTS,
        ])) {
            abort(403);
        }
        $user_id = saveUser(null, null, 'services_id_return');
        $this->services_id = $user_id;

    }

    public function render()
    {
        return view('livewire.admin.pages.financial.definition.index')->layoutData(['page_name' => 'definition_index']);
    }
}
