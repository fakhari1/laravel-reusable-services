<?php

namespace Modules\Finance\Livewire\AccountingDocument\Admin;

use App\Helpers\HelperClass\GeneralUse;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Validator;
use LivewireUI\Modal\ModalComponent;
use Masmerise\Toaster\Toastable;
use Modules\Finance\Models\Detailed;
use Modules\Finance\Models\Document;
use Modules\Project\Models\Order;
use function App\Helpers\saveUser;

class GetReceivedCashPaymentsList extends ModalComponent
{
    use Toastable;
    public $services_id;
    public $order_id;
    public $customer_id;
    public $order;
    public $back;
    public $document;
    public $disable='false';
    public $selected_customer;
    public function mount($order_id=null,$document_id=null,$customer_id=null,$back=null ){
        $check=Order::where('id',$order_id)->first();
        if($check){
            $this->order=$check;
            $this->order_id=$check->id;
        }
        if(!$check){

            $this->customer_id=$customer_id;
            $this->services_id=saveUser(null,null,'services_id_return');
        }else{
            $this->services_id=$check->services_id;
        }


        if($document_id){
            $document=Document::where('id',$document_id)->first();
            if(!$document){
                // return abort(404);
            }else{
                $this->document=$document;
            }
        }else{
            $customer_detailed=Detailed::where('user_id',$this->services_id)->when($this->order,function($q){
                $q->where('customer_id',$this->order->customer_id);
            })->first();

            if($customer_detailed){

                $this->selected_customer= $customer_detailed->id;
                $this->disable='true';
            }
        }
        $this->back=$back;
    }
    protected $rules = [
        'from_cash_id' => 'required',
        'to_cash_id' => 'required',
        'price' => 'required',
        'datevuess' => 'required',
    ];
    public function submit($formData,Filesystem $filesystem) {

        //validate
            $validation=Validator::make($formData,$this->rules);
            if($validation->fails()){
                $this->error($validation->messages()->first());
                return ;
            }
            $formData['from_id']=$formData['from_cash_id'];
            $formData['to_id']=$formData['to_cash_id'];
            //set-data
            if($this->document){
                $document_id=GeneralUse::editDocument($formData,$this->order_id,$this->services_id,'cash_receive',$this->document->id);
            }else{
                $document_id=GeneralUse::makeDocument($formData,$this->order_id,$this->services_id,'cash_receive',$this->customer_id);
            }
            GeneralUse::makeReceipt($document_id,$filesystem);
            if($this->back ){
                return  redirect()->to($this->back)->success(' عملیات با موفقیت انجام گردید');
            }else{
                $this->dispatch('refreshTable');
                $this->dispatch('closeModal');
                $this->dispatch('success', ' عملیات با موفقیت انجام گردید');
                return ;
            }

    }
    public function render()
    {
        $detailed=Detailed::where('user_id',$this->services_id)->latest()->get();
        if($this->document && $this->document->to){
            $detailed_to=$detailed->where('kol_id',$this->document->to->kol_id)->where('moeen_id',$this->document->to->moeen_id);
        }else{
            $detailed_to=$detailed;
        }

        return view('livewire.admin.pages.financial.pay.cash-receive',['detailed'=>$detailed,'detailed_to'=>$detailed_to]);
    }
}
