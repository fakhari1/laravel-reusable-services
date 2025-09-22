<?php

namespace Modules\Finance\Livewire\Invoice\Admin;

use App\Helpers\HelperClass\GeneralUse;
use Illuminate\Filesystem\Filesystem;
use Livewire\Component;
use Masmerise\Toaster\Toastable;
use Modules\Finance\Models\Detailed;
use Modules\Warehouse\Models\Product;
use function App\Helpers\saveUser;

class CreatePurchaseInvoice extends Component
{
    use Toastable;
    public $services_id;
    public $item;
    public $num=1;
    protected $listeners = ['refreshData' => '$refresh'];
    public function mount(){
        $this->services_id=saveUser(null,null,'services_id_return');
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
                  'product_id'=>'0',
                  'count'=>0,
                  'price'=>0,
                  'amount'=>0,
             ]
        ]
    ];
    public function handelRow($type=null)
    {
        if($type == 'increment'){
            $this->num=$this->num+1;
            array_push($this->form['rows'],[
                'code'=>$this->num,
                'detailed'=>'0',
                'product_id'=>'0',
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
                $this->error('لطفا فیلد خرید سطر '.($i+1).' را تکمیل نمایید');
                return ;
            }
            if( $row['product_id'] == '0'){
                $this->error('لطفا فیلد محصول سطر '.($i+1).' را تکمیل نمایید');
                return ;
            }
        }
        GeneralUse::create_buy_factor($this->form,$filesystem,$this->services_id,$this->num);
        //response
        \Redirect::to('/admin/factor/show')->success('عملیات با موفقیت انجام شد');
    }
    public function render()
    {
        $detailed=Detailed::where('user_id', $this->services_id)->with('kol')->whereHas('kol',function($q){
            $q->whereIn('name',['خرید کالا']);
        })->get();
        $products=Product::when(auth()->user()->can( 'employee'),function($q){
            $q->where('tenant_id',auth()->user()->parent);
        },function($q){
            $q->where('tenant_id',auth()->user()->id);
        })->orderBy('id','desc')->get();
        return view('livewire.admin.pages.factor.buy.add',['detailed'=>$detailed,'products'=>$products])->layoutData(['page_name' => 'add_factor']);
    }
}
