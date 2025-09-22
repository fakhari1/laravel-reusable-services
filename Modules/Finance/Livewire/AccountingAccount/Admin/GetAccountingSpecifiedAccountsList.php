<?php

namespace Modules\Finance\Livewire\AccountingAccount\Admin;

use Livewire\Component;
use function App\Helpers\saveUser;

class GetAccountingSpecifiedAccountsList extends Component
{
    public $kol_id;
    public $services_id;
    public function mount($kol_id){

        $this->kol_id=$kol_id;
        $user_id=saveUser(null,null,'services_id_return');
        $this->services_id=$user_id;

    }
    public function render()
    {
        return view('livewire.admin.pages.financial.definition.moeen.index')->layoutData(['page_name' => 'definition_index']);
    }
}
