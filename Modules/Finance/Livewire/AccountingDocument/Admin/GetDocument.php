<?php

namespace Modules\Finance\Livewire\AccountingDocument\Admin;

use Illuminate\Filesystem\Filesystem;
use Livewire\Component;
use Modules\Finance\Models\Document as DocumentM;
use function App\Helpers\destroyFile;
use function App\Helpers\saveUser;

class GetDocument extends Component
{
    public $item;
    public $services_id;
    protected $listeners = ['refreshTable' => '$refresh'];
    public function mount($id){

        $user_id=saveUser(null,null,'services_id_return');
        $check=DocumentM::where('id',$id)->when($user_id,function($q) use ($user_id){
            $q->where('user_id',$user_id);
        })->with(['check','order'])->first();
        if(!$check){
            return abort(404);
        }
        $this->item=$check;
        $this->services_id=$user_id;

    }
    public function deleteDoc(Filesystem $filesystem)
    {
        destroyFile($this->item->receipt,$filesystem);
        DocumentM::where('id',$this->item->id)->delete();
        session()->flash('successRequest', 'عملیات با موفقیت انجام گردید');
        return redirect()->to('/admin/financial/document');
    }
    public function render()
    {
        return view('livewire.admin.pages.financial.document.single')->layoutData(['page_name' => 'document_single']);
    }
}
