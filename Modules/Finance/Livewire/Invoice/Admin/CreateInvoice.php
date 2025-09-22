<?php

namespace Modules\Finance\Livewire\Invoice\Admin;

use App\Helpers\HelperClass\GeneralUse;
use DB;
use Illuminate\Filesystem\Filesystem;
use Livewire\Component;
use Masmerise\Toaster\Toastable;
use Modules\Finance\Models\Detailed;
use Modules\Project\Models\Order;
use Modules\Identity\Models\TenantCustomer;
use function App\Helpers\DateNow;
use function App\Helpers\saveUser;

class CreateInvoice extends Component
{

    use Toastable;
    public $customer;
    public $seller;
    public $services_id;
    public $order;
    public $item;
    public $num=1;
    protected $listeners = ['refreshData' => '$refresh'];
    public function mount($user_id,$order_id=null){
        $this->services_id=saveUser(null,null,'services_id_return');
        if(auth('admin')->check() || auth()->user()->can( 'secretary')){
            $this->customer=TenantCustomer::where('id',$user_id)->first();
            $this->order=Order::where('id',$order_id)->first();
            $this->seller=auth()->user();
        }else{
            $this->customer=TenantCustomer::where('id',$user_id)->where('services_id',$this->services_id)->first();
            $this->order=Order::where('id',$order_id)->where('services_id',$this->services_id)->first();
            if(auth()->user()->can( 'services')){
                $this->seller=auth()->user();
            }else{
                $this->seller=auth()->user()->parentinfo;
            }
        }
        if(!$this->customer){
            return abort(404);
        }
        if(!$this->order && $order_id){
            return abort(404);
        }else{
            $this->form['number']=$this->order->number;
            $this->form['date']=DateNow('format');
            $this->form['tel_seller']=$this->seller->mobile;
            $this->form['seller']=$this->seller->first_name.' '.$this->seller->last_name;
            $this->form['buyer']=$this->customer->first_name.' '.$this->customer->last_name;
            $this->form['tel_buyer']=$this->customer->mobile;
            $this->form['description']=$this->order->description;
        }

    }
    public function handelRow($type=null)
    {
        if($type == 'increment'){
            $this->num=$this->num+1;
            array_push($this->form['rows'],[
                'code'=>$this->num,
                'detailed'=>'0',
                'count'=>0,
                'price'=>0,
                'amount'=>0,
            ]);
        }elseif(1 < $this->num){
            $this->num=$this->num-1;
            unset($this->form['rows'][$this->num]);
        }
        $this->processAll();
    }
    public $form=[
        'number'=>'',
        'date'=>'',
        'tel_seller'=>'',
        'seller'=>'',
        'buyer'=>'',
        'tel_buyer'=>'',
        'total'=>0,
        'discount'=>0,
        'total_price'=>0,
        'description'=>'',
        'rows'=>[
            0 => [
                  'code'=>1,
                  'detailed'=>'0',
                  'count'=>0,
                  'price'=>0,
                  'amount'=>0,
             ]
        ]
    ];
    public static function replaceComma($str){
        $result=str_replace( ',', '', $str );
        return $result;
    }
    public function processAll()
    {
        $total=0;
        foreach ($this->form['rows'] as  $row) {
            $total=$total + (float)$row['amount'];
        }
        $this->form['total']=number_format((float)$total);
        $this->form['total_price']=number_format((float)$this->replaceComma($this->form['total']) - (float)$this->replaceComma($this->form['discount']));
    }
    public function updatedFormTotal()
    {
        $this->form['total_price']=number_format((float)$this->replaceComma($this->form['total']) - (float)$this->replaceComma($this->form['discount']));
    }
    public function updatedFormDiscount()
    {
        $this->form['total_price']=number_format((float)$this->replaceComma($this->form['total']) - (float)$this->replaceComma($this->form['discount']));
    }
    public function handelRows($val,$key,$num)
    {
        if($key == 'detailed'){
            $this->form['rows'][$num]['price']=Detailed::where('id',$val)->first()->price;
            $this->form['rows'][$num]['amount']=(float)$this->form['rows'][$num]['count'] * (float)$this->form['rows'][$num]['price'];
        }
        if($key == 'count'){
            $this->form['rows'][$num]['amount']=(float)$this->form['rows'][$num]['price'] * (float)$this->form['rows'][$num][$key];
        }
        if($key == 'price'){
            $this->form['rows'][$num]['amount']=(float)$this->form['rows'][$num]['count'] * (float)$this->form['rows'][$num][$key];
        }
        $this->processAll();
    }

    public function submit(Filesystem $filesystem)
    {
        foreach ($this->form['rows'] as  $i=>$row) {
            if( $row['detailed'] == '0'){
                $this->error('لطفا فیلد خدمات سطر '.($i+1).' را تکمل نمایید');
                return ;
            }
        }
        GeneralUse::create_factor($this->form,$filesystem,$this->customer->id,$this->order,$this->num);
        if($this->order->user && $this->order->user->device_token){
            $title_push='فاکتور سفارش صادر گردید';
            $body='فاکتور سفارش شماره '.$this->order->number.' صادر گردید';
            GeneralUse::send_push_massage([$this->order->user->device_token],$title_push,$body);
        }
        //response
        session()->flash('successRequest', 'عملیات با موفقیت انجام گردید');
        if($this->order){
            return redirect()->to('/admin/dashboard/order/'.$this->order->id);
        }else{
            return redirect()->to('/admin/factor/show');
        }
    }
    public function render()
    {
        $detailed=Detailed::where('user_id',$this->services_id)->with('kol')->whereHas('kol',function($q){
            $q->whereIn('name',['فروش کالا','درآمد خدمات' ]);
        })->get();

        return view('livewire.admin.pages.factor.add',['detailed'=>$detailed ])->layoutData(['page_name' => 'add_factor']);
    }
}
