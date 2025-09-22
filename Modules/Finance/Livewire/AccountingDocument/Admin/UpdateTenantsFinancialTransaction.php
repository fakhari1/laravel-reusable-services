<?php

namespace Modules\Finance\Livewire\AccountingDocument\Admin;

use Livewire\Component;
use Modules\Identity\Models\TenantCustomer;

class UpdateTenantsFinancialTransaction extends Component
{
    public $item;

    public function mount($id)
    {
        $this->item=TenantCustomer::where('id',$id)->first();
    }

    public function render()
    {
        return view('livewire.admin.pages.table.user.edit-financial')->layoutData(['page_name' => 'show_user']);
    }
}
